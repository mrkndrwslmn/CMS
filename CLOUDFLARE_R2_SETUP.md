# Cloudflare R2 Storage Implementation

This document outlines the complete implementation of Cloudflare R2 storage for the CMS system, replacing local file storage with cloud-based storage.

## Overview

The implementation provides:
- **Centralized file storage** in Cloudflare R2
- **Organized bucket structure** for different file types
- **Seamless migration** from local storage
- **Error handling and fallback** mechanisms
- **Cost-effective storage** with optional CDN integration

## Architecture

### Bucket Structure
```
cms-files/
├── profiles/           # User profile pictures
│   └── {userId}/
├── documents/          # Document uploads  
│   └── {serviceRequestId}/
├── attachments/        # Service request attachments
│   └── {serviceRequestId}/
├── uploads/           # Public user uploads
│   └── {year}/{month}/
└── temp/              # Temporary files
```

### File Organization
- **Profiles**: `profiles/{userId}/profile-{timestamp}.{ext}`
- **Documents**: `documents/{serviceRequestId}/doc-{documentId}-{filename}.{ext}`
- **Attachments**: `attachments/{serviceRequestId}/{uuid}.{ext}`
- **Uploads**: `uploads/{year}/{month}/{userId}-{timestamp}-{filename}.{ext}`

## Setup Instructions

### 1. Cloudflare R2 Configuration

1. **Create R2 Bucket**:
   - Go to Cloudflare Dashboard → R2 Object Storage
   - Create a new bucket (e.g., `cms-files`)
   - Configure public access if needed

2. **Generate API Credentials**:
   - Go to R2 → Manage R2 API tokens
   - Create token with permissions:
     - Object Read
     - Object Write
     - Object Delete
   - Note down Access Key ID and Secret Access Key

3. **Optional: Custom Domain**:
   - Set up a custom domain for your R2 bucket
   - Configure DNS and SSL settings

### 2. Laravel Configuration

1. **Environment Variables**:
   Add these to your `.env` file:
   ```env
   # Cloudflare R2 Configuration
   CLOUDFLARE_R2_ACCESS_KEY_ID=your_r2_access_key_id_here
   CLOUDFLARE_R2_SECRET_ACCESS_KEY=your_r2_secret_access_key_here
   CLOUDFLARE_R2_BUCKET=cms-files
   CLOUDFLARE_R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
   CLOUDFLARE_R2_URL=https://your-custom-domain.com
   CLOUDFLARE_R2_ACCOUNT_ID=your_cloudflare_account_id
   
   # Optional: Set R2 as default filesystem
   FILESYSTEM_DISK=r2
   ```

2. **Test Configuration**:
   ```bash
   php artisan r2:test
   ```

### 3. Database Migration

Run the migration to add the `file_url` column:
```bash
php artisan migrate
```

## File Migration

### Migrate Existing Files

1. **Dry Run** (recommended first):
   ```bash
   php artisan migrate:files-to-r2 --dry-run
   ```

2. **Migrate All Files**:
   ```bash
   php artisan migrate:files-to-r2
   ```

3. **Migrate Specific Types**:
   ```bash
   # Only documents
   php artisan migrate:files-to-r2 --type=documents
   
   # Only attachments
   php artisan migrate:files-to-r2 --type=attachments
   ```

4. **Customize Chunk Size**:
   ```bash
   php artisan migrate:files-to-r2 --chunk=100
   ```

## Implementation Details

### CloudflareR2Service

The `CloudflareR2Service` class provides:

- **File Upload Methods**:
  - `uploadFile()` - Generic file upload
  - `uploadProfilePicture()` - Profile picture upload
  - `uploadDocument()` - Document upload
  - `uploadAttachment()` - Service request attachment
  - `uploadPublicFile()` - Public user upload

- **File Management**:
  - `deleteFile()` - Delete file from R2
  - `fileExists()` - Check if file exists
  - `getFileSize()` - Get file size
  - `getFileMetadata()` - Get file metadata

- **URL Generation**:
  - `getPublicUrl()` - Get public URL
  - `getSignedUrl()` - Get temporary signed URL

- **Migration Support**:
  - `copyFromLocal()` - Copy file from local storage
  - `testConnection()` - Test R2 connectivity

### Controller Updates

**File Upload Controllers**:
- `PublicServiceRequestController` - Updated for public uploads
- `ServiceRequestController` - Updated for attachments
- All controllers now use `CloudflareR2Service`

**Download Handlers**:
- Automatic detection of R2 vs local files
- Redirect to R2 URLs for direct download
- Fallback to local storage for legacy files

### Model Enhancements

**Document Model**:
- `isR2File()` - Check if file is in R2
- `getDownloadUrl()` - Get appropriate download URL
- `fileExists()` - Check file existence
- Updated `getFormattedSizeAttribute()` for R2 compatibility

## Usage Examples

### Upload Files in Controllers

```php
use App\Services\CloudflareR2Service;

public function uploadDocument(Request $request)
{
    $r2Service = new CloudflareR2Service();
    
    foreach ($request->file('documents') as $file) {
        $result = $r2Service->uploadDocument($file, $serviceRequestId);
        
        if ($result['success']) {
            // Save to database
            Document::create([
                'fileName' => $result['original_name'],
                'filePath' => $result['url'], // R2 URL
                'fileSize' => $result['size'],
                'fileType' => $result['mime_type'],
                // ... other fields
            ]);
        }
    }
}
```

### Check File Types in Views

```blade
@if($document->isR2File())
    <a href="{{ $document->filePath }}" target="_blank">View Document</a>
@else
    <a href="{{ route('documents.download', $document->id) }}">Download Document</a>
@endif
```

## Security Considerations

### File Access Control
- **Public Files**: Direct R2 URLs for public access
- **Private Files**: Use signed URLs with expiration
- **Access Control**: Maintain existing permission logic

### Example Private File Access
```php
$r2Service = new CloudflareR2Service();
$signedUrl = $r2Service->getSignedUrl($filePath, 60); // 60 minutes expiry
```

## Performance Optimization

### CDN Integration
1. Set up Cloudflare CDN for your R2 bucket
2. Configure cache settings for static files
3. Use custom domain for CDN endpoint

### Caching
- R2 URLs are cached in database
- File metadata cached when needed
- Automatic cleanup of expired signed URLs

## Monitoring and Maintenance

### Health Checks
- Use `php artisan r2:test` for connection testing
- Monitor upload/download success rates
- Track file migration progress

### Backup Strategy
- R2 provides built-in durability
- Consider cross-region replication for critical files
- Maintain audit logs for file operations

## Cost Optimization

### Storage Classes
- Use appropriate storage classes for different file types
- Configure lifecycle policies for old files
- Monitor storage usage and costs

### Best Practices
- Compress images before upload
- Set appropriate file size limits
- Clean up temporary files regularly

## Troubleshooting

### Common Issues

1. **Connection Failures**:
   - Verify API credentials
   - Check bucket permissions
   - Validate endpoint URL

2. **Upload Errors**:
   - Check file size limits
   - Verify MIME type restrictions
   - Monitor network connectivity

3. **Download Issues**:
   - Validate file URLs
   - Check CORS settings
   - Verify bucket public access

### Debug Commands
```bash
# Test R2 connection
php artisan r2:test

# Check migration status
php artisan migrate:files-to-r2 --dry-run

# Monitor logs
php artisan pail
```

## Migration Checklist

- [ ] Configure R2 bucket and credentials
- [ ] Update `.env` file with R2 settings
- [ ] Test R2 connection with `php artisan r2:test`
- [ ] Run database migrations
- [ ] Perform dry-run file migration
- [ ] Execute actual file migration
- [ ] Verify file access in application
- [ ] Update any hardcoded file paths
- [ ] Set up monitoring and alerts
- [ ] Document any custom configurations

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify R2 configuration and credentials
3. Test with simple file upload/download
4. Check Cloudflare R2 dashboard for errors