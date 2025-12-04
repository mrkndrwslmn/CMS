<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class CloudflareR2Service
{
    private $disk;
    private $bucket;

    public function __construct()
    {
        $this->disk = Storage::disk('r2');
        $this->bucket = config('filesystems.disks.r2.bucket');
    }

    /**
     * Upload a file to Cloudflare R2
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $filename
     * @param array $metadata
     * @return array
     */
    public function uploadFile(UploadedFile $file, string $directory, ?string $filename = null, array $metadata = []): array
    {
        try {
            // Generate filename if not provided
            if (!$filename) {
                $filename = $this->generateUniqueFileName($file);
            }

            // Ensure directory ends with /
            $directory = rtrim($directory, '/') . '/';
            
            // Full path in bucket
            $path = $directory . $filename;

            // Upload to R2
            $result = $this->disk->put($path, file_get_contents($file->getRealPath()), [
                'visibility' => 'public',
                'ContentType' => $file->getMimeType(),
                'Metadata' => array_merge([
                    'original_name' => $file->getClientOriginalName(),
                    'upload_timestamp' => now()->toISOString(),
                ], $metadata)
            ]);

            if (!$result) {
                throw new Exception('Failed to upload file to R2');
            }

            return [
                'success' => true,
                'path' => $path,
                'url' => $this->getPublicUrl($path),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => $filename,
            ];

        } catch (Exception $e) {
            Log::error('R2 Upload Error: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'directory' => $directory,
                'filename' => $filename,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Upload profile picture
     *
     * @param UploadedFile $file
     * @param int $userId
     * @return array
     */
    public function uploadProfilePicture(UploadedFile $file, int $userId): array
    {
        $directory = "profiles/{$userId}";
        $filename = 'profile-' . time() . '.' . $file->getClientOriginalExtension();
        
        return $this->uploadFile($file, $directory, $filename, [
            'type' => 'profile_picture',
            'user_id' => $userId,
        ]);
    }

    /**
     * Upload document
     *
     * @param UploadedFile $file
     * @param int $serviceRequestId
     * @param int|null $documentId
     * @return array
     */
    public function uploadDocument(UploadedFile $file, int $serviceRequestId, ?int $documentId = null): array
    {
        $directory = "documents/{$serviceRequestId}";
        $prefix = $documentId ? "doc-{$documentId}-" : 'doc-' . time() . '-';
        $filename = $prefix . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        
        return $this->uploadFile($file, $directory, $filename, [
            'type' => 'document',
            'service_request_id' => $serviceRequestId,
            'document_id' => $documentId,
        ]);
    }

    /**
     * Upload service request attachment
     *
     * @param UploadedFile $file
     * @param int $serviceRequestId
     * @return array
     */
    public function uploadAttachment(UploadedFile $file, int $serviceRequestId): array
    {
        $directory = "attachments/{$serviceRequestId}";
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        return $this->uploadFile($file, $directory, $filename, [
            'type' => 'attachment',
            'service_request_id' => $serviceRequestId,
        ]);
    }

    /**
     * Upload public user upload
     *
     * @param UploadedFile $file
     * @param int $userId
     * @return array
     */
    public function uploadPublicFile(UploadedFile $file, int $userId): array
    {
        $directory = "uploads/" . date('Y/m');
        $filename = $userId . '-' . time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        
        return $this->uploadFile($file, $directory, $filename, [
            'type' => 'public_upload',
            'user_id' => $userId,
        ]);
    }

    /**
     * Delete a file from R2
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        try {
            return $this->disk->delete($path);
        } catch (Exception $e) {
            Log::error('R2 Delete Error: ' . $e->getMessage(), ['path' => $path]);
            return false;
        }
    }

    /**
     * Extract the R2 path from a full URL
     *
     * @param string $url
     * @return string|null
     */
    public function extractPathFromUrl(string $url): ?string
    {
        // Get the custom domain URL from config
        $customDomain = config('filesystems.disks.r2.url');
        $bucket = config('filesystems.disks.r2.bucket');
        
        // Try to extract path from custom domain URL
        if ($customDomain && str_starts_with($url, $customDomain)) {
            return ltrim(str_replace($customDomain, '', $url), '/');
        }
        
        // Try to extract from URLs containing the bucket name
        // Pattern: https://*.r2.cloudflarestorage.com/bucket/path or https://*.r2.dev/path
        if (preg_match('#https?://[^/]+/' . preg_quote($bucket, '#') . '/(.+)$#', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern for public R2 dev URLs: https://pub-xxx.r2.dev/path
        if (preg_match('#https?://pub-[^.]+\.r2\.dev/(.+)$#', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern for direct R2 storage URLs
        if (preg_match('#https?://[^/]+\.r2\.cloudflarestorage\.com/[^/]+/(.+)$#', $url, $matches)) {
            return $matches[1];
        }
        
        Log::warning('Could not extract R2 path from URL', ['url' => $url]);
        return null;
    }

    /**
     * Delete a file from R2 using its full URL
     *
     * @param string $url
     * @return bool
     */
    public function deleteFileByUrl(string $url): bool
    {
        $path = $this->extractPathFromUrl($url);
        
        if (!$path) {
            Log::error('Cannot delete R2 file: unable to extract path from URL', ['url' => $url]);
            return false;
        }
        
        return $this->deleteFile($path);
    }

    /**
     * Get public URL for a file
     *
     * @param string $path
     * @return string
     */
    public function getPublicUrl(string $path): string
    {
        $customDomain = config('filesystems.disks.r2.url');
        
        if ($customDomain) {
            return rtrim($customDomain, '/') . '/' . ltrim($path, '/');
        }

        // Fallback to R2 endpoint URL
        $endpoint = config('filesystems.disks.r2.endpoint');
        $bucket = config('filesystems.disks.r2.bucket');
        
        return rtrim($endpoint, '/') . '/' . $bucket . '/' . ltrim($path, '/');
    }

    /**
     * Get a temporary signed URL for private files
     *
     * @param string $path
     * @param int $expirationMinutes
     * @return string
     */
    public function getSignedUrl(string $path, int $expirationMinutes = 60): string
    {
        try {
            return $this->disk->temporaryUrl($path, now()->addMinutes($expirationMinutes));
        } catch (Exception $e) {
            Log::error('R2 Signed URL Error: ' . $e->getMessage(), ['path' => $path]);
            return $this->getPublicUrl($path);
        }
    }

    /**
     * Check if file exists in R2
     *
     * @param string $path
     * @return bool
     */
    public function fileExists(string $path): bool
    {
        try {
            return $this->disk->exists($path);
        } catch (Exception $e) {
            Log::error('R2 File Exists Check Error: ' . $e->getMessage(), ['path' => $path]);
            return false;
        }
    }

    /**
     * Get file size
     *
     * @param string $path
     * @return int|false
     */
    public function getFileSize(string $path)
    {
        try {
            return $this->disk->size($path);
        } catch (Exception $e) {
            Log::error('R2 File Size Error: ' . $e->getMessage(), ['path' => $path]);
            return false;
        }
    }

    /**
     * Copy file from local storage to R2
     *
     * @param string $localPath
     * @param string $r2Path
     * @return bool
     */
    public function copyFromLocal(string $localPath, string $r2Path): bool
    {
        try {
            if (!file_exists($localPath)) {
                return false;
            }

            $content = file_get_contents($localPath);
            $mimeType = mime_content_type($localPath) ?: 'application/octet-stream';

            return $this->disk->put($r2Path, $content, [
                'visibility' => 'public',
                'ContentType' => $mimeType,
            ]);

        } catch (Exception $e) {
            Log::error('R2 Copy From Local Error: ' . $e->getMessage(), [
                'local_path' => $localPath,
                'r2_path' => $r2Path,
            ]);
            return false;
        }
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateUniqueFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $basename = Str::slug($basename);
        
        return uniqid() . '-' . $basename . '.' . $extension;
    }

    /**
     * Get disk instance
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * List files in directory
     *
     * @param string $directory
     * @return array
     */
    public function listFiles(string $directory = ''): array
    {
        try {
            return $this->disk->files($directory);
        } catch (Exception $e) {
            Log::error('R2 List Files Error: ' . $e->getMessage(), ['directory' => $directory]);
            return [];
        }
    }

    /**
     * Get file metadata
     *
     * @param string $path
     * @return array|null
     */
    public function getFileMetadata(string $path): ?array
    {
        try {
            if (!$this->fileExists($path)) {
                return null;
            }

            return [
                'path' => $path,
                'size' => $this->getFileSize($path),
                'url' => $this->getPublicUrl($path),
                'last_modified' => $this->disk->lastModified($path),
            ];
        } catch (Exception $e) {
            Log::error('R2 Get Metadata Error: ' . $e->getMessage(), ['path' => $path]);
            return null;
        }
    }

    /**
     * Test R2 connection
     *
     * @return array
     */
    public function testConnection(): array
    {
        try {
            // Try to create a test file
            $testPath = 'test/connection-test-' . time() . '.txt';
            $testContent = 'R2 connection test at ' . now()->toISOString();

            $result = $this->disk->put($testPath, $testContent);
            
            if ($result) {
                // Clean up test file
                $this->disk->delete($testPath);
                
                return [
                    'success' => true,
                    'message' => 'R2 connection successful',
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to write test file to R2',
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'R2 connection failed: ' . $e->getMessage(),
            ];
        }
    }
}