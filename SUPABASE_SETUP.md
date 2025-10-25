# Supabase Setup Guide for Treis Adiutor CMS

## Prerequisites

1. **Supabase Account**: Create a free account at [https://supabase.com](https://supabase.com)
2. **PHP >= 8.1** with PostgreSQL PDO extension
3. **Composer** for dependency management

## Step 1: Create Supabase Project

1. Log in to your Supabase dashboard
2. Click "New Project"
3. Choose your organization
4. Fill in project details:
   - **Project Name**: `treisadiutor-cms`
   - **Database Password**: Create a strong password (save this!)
   - **Region**: Choose closest to your location
5. Click "Create new project"

## Step 2: Get Supabase Credentials

After your project is created, go to **Settings > API** and copy the following:

1. **Project URL**: `https://agojrxautplzmceawmix.supabase.co`
2. **Anon Public Key**: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`
3. **Service Role Key**: `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...` (Keep this secret!)

Go to **Settings > Database** and copy:
4. **Host**: `db.your-project-ref.supabase.co`
5. **Database Password**: The one you created earlier
6. **JWT Secret**: Found in **Settings > API > JWT Settings**

## Step 3: Install Required PHP Extensions

### Windows (XAMPP/WAMP)
```bash
# Make sure these extensions are enabled in php.ini
extension=pdo_pgsql
extension=pgsql
```

### Ubuntu/Debian
```bash
sudo apt-get install php-pgsql php-pdo-pgsql
```

### macOS (Homebrew)
```bash
brew install php
# PostgreSQL extensions should be included
```

## Step 4: Install Composer Dependencies

```bash
cd your-project-directory
composer install
```

If you need PostgreSQL driver:
```bash
composer require doctrine/dbal
```

## Step 5: Configure Environment Variables

Update your `.env` file with your Supabase credentials:

```env
# Database Configuration
DB_CONNECTION=supabase

# Supabase Configuration
SUPABASE_URL=https://your-project-ref.supabase.co
SUPABASE_ANON_KEY=your_supabase_anon_key_here
SUPABASE_SERVICE_KEY=your_supabase_service_role_key_here
SUPABASE_JWT_SECRET=your_supabase_jwt_secret_here

# Supabase Database Configuration
SUPABASE_DB_HOST=db.your-project-ref.supabase.co
SUPABASE_DB_PORT=5432
SUPABASE_DB_DATABASE=postgres
SUPABASE_DB_USERNAME=postgres
SUPABASE_DB_PASSWORD=your_database_password_here

# Alternative: Direct Database URL
DATABASE_URL=postgresql://postgres:your_password@db.your-project-ref.supabase.co:5432/postgres
```

## Step 6: Test Database Connection

```bash
php artisan tinker
```

Then run:
```php
DB::connection()->getPdo();
// Should return PDO object without errors
```

## Step 7: Run Database Migrations

```bash
# Run all migrations
php artisan migrate

# If you want to see what will be migrated first
php artisan migrate --pretend

# Run with confirmation
php artisan migrate --force
```

## Step 8: Seed Initial Data (Optional)

Create an admin user:
```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'fullName' => 'Admin User',
    'email' => 'admin@treisadiutor.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
    'status' => 'active',
    'phoneNumber' => '+1234567890'
]);
```

## Step 9: Configure Supabase Storage (Optional)

For file uploads, create storage buckets in Supabase:

1. Go to **Storage** in your Supabase dashboard
2. Create new bucket: `ta-cms`
3. Set appropriate policies for file access

Update your `.env`:
```env
SUPABASE_STORAGE_BUCKET=ta-cms
```

## Step 10: Set up Row Level Security (RLS)

In your Supabase SQL Editor, run:

```sql
-- Enable RLS on sensitive tables
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE forms ENABLE ROW LEVEL SECURITY;
ALTER TABLE tasks ENABLE ROW LEVEL SECURITY;
ALTER TABLE feedbacks ENABLE ROW LEVEL SECURITY;

-- Create policies as needed
CREATE POLICY "Users can view their own data" ON users
    FOR SELECT USING (auth.uid() = id::text);

-- Add more policies based on your security requirements
```

## Common Issues & Solutions

### 1. "Could not find driver" Error (PostgreSQL PDO)

This happens when you have multiple PHP installations or PHP is loading the wrong php.ini file.

**Solution 1: Set PHPRC Environment Variable (Temporary)**
```bash
# In PowerShell
$env:PHPRC="C:\xampp1\php"

# In Command Prompt
set PHPRC=C:\xampp1\php
```

**Solution 2: Set PHPRC Permanently**
1. Press `Win + R`, type `sysdm.cpl`, press Enter
2. Go to "Advanced" tab → "Environment Variables"
3. Under "System Variables", click "New"
4. Variable name: `PHPRC`
5. Variable value: `C:\xampp1\php` (or your PHP directory)
6. Click OK and restart your terminal

**Solution 3: Copy php.ini to the correct location**
```bash
copy "C:\xampp1\php\php.ini" "C:\xampp\php\php.ini"
```

**Verify the fix:**
```bash
# Check loaded extensions
php -m | findstr -i pgsql
# Should show: pdo_pgsql and pgsql

# Check php.ini location
php --ini
```

### 2. Connection Refused
- Check if PostgreSQL extension is installed
- Verify your database credentials  
- Ensure your IP is whitelisted (Supabase allows all by default)

### 3. Migration Errors
```bash
# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Recreate database (if safe to do so)
php artisan migrate:fresh
```

### 3. SSL Connection Issues
Add to your database configuration:
```php
'options' => [
    PDO::ATTR_SSLMODE => 'require',
]
```

## Verification Commands

```bash
# Check database connection
php artisan migrate:status

# List all tables
php artisan tinker
>>> DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");

# Test user creation
php artisan tinker
>>> App\Models\User::count();
```

## Security Notes

1. **Never commit** your `.env` file to version control
2. **Use strong passwords** for your database
3. **Rotate service keys** regularly
4. **Set up proper RLS policies** in production
5. **Use HTTPS** in production environments

## Next Steps

1. Run the application: `php artisan serve`
2. Visit admin login: `http://localhost:8000/admin/login`
3. Create your first admin user
4. Start using the CMS!

For any issues, check the Laravel logs in `storage/logs/laravel.log` and Supabase logs in your dashboard.