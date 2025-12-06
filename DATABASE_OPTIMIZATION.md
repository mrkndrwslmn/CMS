# Database Performance Optimization Guide

## Problem
Pages were taking 5-25 seconds to load due to inefficient database queries.

## Optimizations Implemented

### 1. Dashboard Statistics Caching (`app/Services/DashboardStatsService.php`)

**Before:** Each dashboard made 7-10 separate `COUNT()` and `SUM()` queries.

**After:** 
- Batched queries using `selectRaw()` with `CASE WHEN` statements
- Results cached for 1-2 minutes per user
- Reduces database calls from 10+ to 1 (cached)

```php
// Single query replaces 4 separate count queries
$userCounts = DB::table('users')
    ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN role = 'client' THEN 1 ELSE 0 END) as clients,
        SUM(CASE WHEN role = 'adiutor' THEN 1 ELSE 0 END) as adiutors
    ")
    ->first();
```

### 2. Query Optimization in Controllers

**AdminController:**
- Added caching for chart data (5 minutes)
- Optimized eager loading with specific columns
- Reduced `BudgetChangeRequest` query columns

**ClientController:**
- Removed memory limit increase (no longer needed)
- Eliminated `unique()->take()` pattern (moved filtering to SQL)
- Cached announcements per user (5 minutes)
- Selected only needed columns in queries

**AdiutorController:**
- Cached monthly earnings (5 minutes)
- Cached announcements (5 minutes)
- Optimized all list queries with specific column selection

### 3. Database Indexes Added (`database/migrations/2025_12_05_220000_add_dashboard_performance_indexes.php`)

New indexes for commonly filtered columns:

| Table | Index | Columns | Use Case |
|-------|-------|---------|----------|
| `users` | `idx_users_role_created` | role, created_at | Role filtering + sorting |
| `service_requests` | `idx_service_requests_client_status` | client_id, status | Client dashboard |
| `projects` | `idx_projects_client_status` | client_id, status | Client projects list |
| `payments` | `idx_payments_client_status` | client_id, status | Client payment queries |
| `budget_change_requests` | `idx_budget_requests_adiutor_status` | adiutor_id, status | Adiutor requests |
| `tasks` | `idx_tasks_deadline_status` | deadline, status | Urgent tasks query |
| `tasks` | `idx_tasks_assigned_deadline` | assignedTo, deadline, status | Adiutor urgent tasks |
| `announcements` | `idx_announcements_active` | status, target_audience, expires_at | Active announcements |

### 4. Existing Indexes (Already Present)

The codebase already has good index coverage from previous migrations:
- `2024_12_05_000001_add_performance_indexes.php`
- `2025_12_04_181228_add_performance_indexes_to_project_tables.php`
- `2025_12_04_185202_add_financial_performance_indexes.php`
- `2025_12_04_193444_add_communication_system_indexes.php`

## How to Apply Optimizations

### Step 1: Run Database Migrations
```bash
php artisan migrate
```

### Step 2: Clear and Warm Cache (Optional)
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

### Step 3: Verify Cache is Working
Check your `.env` file:
```
CACHE_STORE=database  # or redis for better performance
```

For production, consider using Redis:
```
CACHE_STORE=redis
```

## Expected Performance Improvements

| Page | Before | After |
|------|--------|-------|
| Admin Dashboard | 5-10s | <1s |
| Client Dashboard | 8-15s | <1s |
| Adiutor Dashboard | 5-12s | <1s |
| Project List | 3-8s | <0.5s |
| Task List | 3-6s | <0.5s |

## Cache Invalidation

Caches are automatically invalidated:
- **Dashboard stats**: Every 1-2 minutes (TTL-based)
- **Chart data**: Every 5 minutes (TTL-based)
- **Announcements**: Every 5 minutes (TTL-based)

For manual invalidation when data changes significantly:
```php
app(DashboardStatsService::class)->invalidateAdminStats();
app(DashboardStatsService::class)->invalidateClientStats($userId);
app(DashboardStatsService::class)->invalidateAdiutorStats($userId);
```

## Additional Recommendations

### 1. Enable Query Caching in MySQL
Add to MySQL config:
```ini
query_cache_type = 1
query_cache_size = 64M
```

### 2. Consider Redis for Sessions/Cache
```
CACHE_STORE=redis
SESSION_DRIVER=redis
```

### 3. Enable OPcache
Ensure PHP OPcache is enabled in production:
```ini
opcache.enable=1
opcache.memory_consumption=256
```

### 4. Database Connection Pooling
For high-traffic sites, consider connection pooling with:
- PgBouncer (PostgreSQL)
- ProxySQL (MySQL)

## Monitoring

To identify slow queries in development:
```php
// In AppServiceProvider::boot()
if (app()->environment('local')) {
    DB::listen(function ($query) {
        if ($query->time > 100) { // Log queries over 100ms
            Log::warning('Slow Query', [
                'sql' => $query->sql,
                'time' => $query->time,
                'bindings' => $query->bindings
            ]);
        }
    });
}
```
