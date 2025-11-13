# Coupon and Loyalty System - Implementation Plan

## Project Analysis Summary

### Current Architecture
- **Framework**: Laravel 12.0 (PHP 8.2+)
- **Frontend**: Blade templates + Tailwind CSS 4.x + Alpine.js
- **Payment System**: Maya Payment Gateway (integrated)
- **Authentication**: Multi-role system (Admin, Client, Adiutor)
- **Database**: MySQL with Eloquent ORM

### Current Payment Flow
1. Client submits service request
2. Admin reviews and approves with budget
3. Service request transitions to `pending_payment` status
4. Client pays via Maya Payment Gateway
5. Payment confirmation triggers project creation
6. Payment types supported: `full_payment`, `milestone_payment`, `downpayment`

---

## 📋 IMPLEMENTATION PLAN

## Phase 1: Database Schema Design

### 1.1 Coupons Table
**File**: `database/migrations/2025_11_13_000001_create_coupons_table.php`

```php
Schema::create('coupons', function (Blueprint $table) {
    $table->id();
    $table->string('code', 50)->unique(); // e.g., "WELCOME2025", "LOYAL50"
    $table->string('name'); // Display name
    $table->text('description')->nullable();
    
    // Discount Configuration
    $table->enum('discount_type', ['percentage', 'fixed_amount']);
    $table->decimal('discount_value', 10, 2); // % or fixed PHP amount
    $table->decimal('max_discount_amount', 10, 2)->nullable(); // Cap for percentage discounts
    $table->decimal('min_purchase_amount', 10, 2)->default(0); // Minimum spend requirement
    
    // Coupon Type & Visibility
    $table->enum('coupon_type', ['public', 'user_specific', 'request_specific']);
    $table->foreignId('specific_user_id')->nullable()->constrained('users')->onDelete('cascade');
    $table->foreignId('specific_request_id')->nullable()->constrained('service_requests')->onDelete('cascade');
    
    // Usage Limits
    $table->integer('max_total_uses')->nullable(); // Global usage limit
    $table->integer('max_uses_per_user')->default(1); // Per-user limit
    $table->integer('current_uses')->default(0); // Track total uses
    
    // Validity Period
    $table->timestamp('valid_from')->nullable();
    $table->timestamp('valid_until')->nullable();
    
    // Status & Metadata
    $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
    $table->foreignId('created_by')->constrained('users'); // Admin who created
    $table->text('admin_notes')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes
    $table->index(['code', 'status']);
    $table->index(['coupon_type', 'status']);
    $table->index(['valid_from', 'valid_until']);
});
```

### 1.2 Coupon Usage Tracking Table
**File**: `database/migrations/2025_11_13_000002_create_coupon_usages_table.php`

```php
Schema::create('coupon_usages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
    $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
    
    $table->decimal('original_amount', 10, 2);
    $table->decimal('discount_amount', 10, 2);
    $table->decimal('final_amount', 10, 2);
    
    $table->timestamp('used_at');
    $table->timestamps();
    
    // Indexes
    $table->index(['user_id', 'coupon_id']);
    $table->index('service_request_id');
});
```

### 1.3 Loyalty Points Table
**File**: `database/migrations/2025_11_13_000003_create_loyalty_system_tables.php`

```php
// Loyalty Points Balance
Schema::create('loyalty_points', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
    $table->integer('total_points')->default(0);
    $table->integer('available_points')->default(0); // Points not locked/redeemed
    $table->integer('lifetime_earned')->default(0);
    $table->integer('lifetime_redeemed')->default(0);
    $table->enum('tier', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
    $table->timestamp('tier_achieved_at')->nullable();
    $table->timestamps();
});

// Loyalty Points Transactions Log
Schema::create('loyalty_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->enum('transaction_type', ['earned', 'redeemed', 'expired', 'adjusted']);
    $table->integer('points'); // Can be positive (earned) or negative (redeemed)
    $table->string('source'); // e.g., 'payment_completed', 'referral', 'milestone', 'coupon_redemption'
    $table->text('description');
    
    // Related Records
    $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->onDelete('set null');
    $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
    $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
    
    $table->integer('balance_after'); // Running balance
    $table->timestamp('expires_at')->nullable(); // For earned points
    $table->timestamps();
    
    // Indexes
    $table->index(['user_id', 'transaction_type']);
    $table->index('expires_at');
});

// Loyalty Tiers Configuration (optional, can be in config)
Schema::create('loyalty_tiers', function (Blueprint $table) {
    $table->id();
    $table->enum('tier', ['bronze', 'silver', 'gold', 'platinum'])->unique();
    $table->integer('points_required');
    $table->integer('discount_percentage'); // Default discount for tier
    $table->json('benefits'); // JSON array of benefits
    $table->timestamps();
});
```

### 1.4 Service Requests Table Updates
**File**: `database/migrations/2025_11_13_000004_add_coupon_loyalty_to_service_requests.php`

```php
Schema::table('service_requests', function (Blueprint $table) {
    // Coupon Application
    $table->foreignId('applied_coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
    $table->decimal('original_approved_budget', 10, 2)->nullable(); // Before coupon
    $table->decimal('coupon_discount_amount', 10, 2)->default(0);
    $table->timestamp('coupon_applied_at')->nullable();
    
    // Loyalty Points
    $table->integer('loyalty_points_used')->default(0);
    $table->decimal('loyalty_discount_amount', 10, 2)->default(0);
    $table->integer('loyalty_points_earned')->default(0); // Points to be awarded on completion
    $table->boolean('loyalty_points_awarded')->default(false);
});
```

---

## Phase 2: Backend Models & Business Logic

### 2.1 Coupon Model
**File**: `app/Models/Coupon.php`

Key methods:
```php
- isValid(): bool                          // Check all validity conditions
- canBeUsedBy(User $user): bool           // Check user eligibility
- calculateDiscount(float $amount): float  // Calculate discount amount
- apply(ServiceRequest $request): void     // Apply coupon to request
- incrementUsage(): void                   // Track usage
- isExpired(): bool
- hasUsageLeft(): bool
- getVisibleToUser(User $user): Collection // Static: Get coupons for user
```

### 2.2 LoyaltyPoint Model
**File**: `app/Models/LoyaltyPoint.php`

Key methods:
```php
- earnPoints(int $points, string $source, $relatedModel): void
- redeemPoints(int $points, string $reason, $relatedModel): void
- getAvailablePoints(): int
- calculateTier(): string
- updateTier(): void
- getPointsToNextTier(): int
```

### 2.3 LoyaltyTransaction Model
**File**: `app/Models/LoyaltyTransaction.php`

Tracking model for audit trail.

### 2.4 Service Layer: CouponService
**File**: `app/Services/CouponService.php`

```php
class CouponService
{
    public function validateCoupon(string $code, User $user, float $amount): array
    public function applyCouponToRequest(ServiceRequest $request, Coupon $coupon): void
    public function removeCouponFromRequest(ServiceRequest $request): void
    public function calculateFinalAmount(ServiceRequest $request): float
    public function getAvailableCouponsForUser(User $user): Collection
    public function autoAssignCouponToRequest(ServiceRequest $request, ?Coupon $coupon): void
}
```

### 2.5 Service Layer: LoyaltyService
**File**: `app/Services/LoyaltyService.php`

```php
class LoyaltyService
{
    // Points Earning Logic
    public function calculatePointsForPayment(Payment $payment): int
    {
        // Example: 1 point per ₱100 spent
        // Bronze: 1%, Silver: 2%, Gold: 3%, Platinum: 5%
    }
    
    public function awardPointsForCompletion(ServiceRequest $request): void
    public function awardMilestoneBonus(Project $project): void
    
    // Points Redemption Logic
    public function convertPointsToDiscount(int $points): float
    {
        // Example: 100 points = ₱100 discount
    }
    
    public function applyLoyaltyDiscount(ServiceRequest $request, int $points): void
    
    // Tier Management
    public function checkAndUpgradeTier(User $user): void
    public function getTierBenefits(string $tier): array
    
    // Gamification
    public function checkAchievements(User $user): array // Badges, milestones
}
```

---

## Phase 3: Admin Controllers & Routes

### 3.1 Admin Coupon Management Controller
**File**: `app/Http/Controllers/Admin/CouponController.php`

```php
class CouponController extends Controller
{
    public function index()              // List all coupons with filters
    public function create()             // Show create form
    public function store(Request $request) // Create new coupon
    public function show(Coupon $coupon) // View coupon details + usage stats
    public function edit(Coupon $coupon) // Edit form
    public function update(Request $request, Coupon $coupon)
    public function destroy(Coupon $coupon) // Soft delete
    public function toggleStatus(Coupon $coupon) // Activate/deactivate
    public function usageHistory(Coupon $coupon) // View usage logs
    public function bulkGenerate(Request $request) // Generate multiple codes
}
```

### 3.2 Admin Loyalty Management Controller
**File**: `app/Http/Controllers/Admin/LoyaltyController.php`

```php
class LoyaltyController extends Controller
{
    public function index()              // View all users' loyalty stats
    public function show(User $user)     // View specific user loyalty details
    public function adjustPoints(Request $request, User $user) // Manual adjustment
    public function tierSettings()       // Configure tier thresholds
    public function updateTierSettings(Request $request)
    public function exportLoyaltyReport() // Export CSV/Excel
}
```

### 3.3 Update RequestManagementController
**File**: `app/Http/Controllers/Admin/RequestManagementController.php`

**Modify `approve()` method:**
```php
public function approve(Request $request, $id)
{
    $request->validate([
        // ... existing validation
        'attach_coupon' => 'nullable|boolean',
        'coupon_id' => 'nullable|exists:coupons,id',
        'create_new_coupon' => 'nullable|boolean',
        'new_coupon_data' => 'nullable|array', // For inline coupon creation
    ]);
    
    // ... existing approval logic
    
    // Handle coupon attachment
    if ($request->attach_coupon) {
        if ($request->create_new_coupon) {
            $coupon = $this->couponService->createRequestSpecificCoupon($request->new_coupon_data, $serviceRequest);
        } elseif ($request->coupon_id) {
            $coupon = Coupon::findOrFail($request->coupon_id);
        }
        
        if (isset($coupon)) {
            $this->couponService->autoAssignCouponToRequest($serviceRequest, $coupon);
        }
    }
    
    // ... rest of logic
}
```

### 3.4 Routes Addition
**File**: `routes/web.php`

```php
// Admin Coupon Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::post('coupons/{coupon}/toggle', [CouponController::class, 'toggleStatus'])->name('coupons.toggle');
    Route::get('coupons/{coupon}/usage', [CouponController::class, 'usageHistory'])->name('coupons.usage');
    Route::post('coupons/bulk-generate', [CouponController::class, 'bulkGenerate'])->name('coupons.bulk');
    
    // Admin Loyalty Routes
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [LoyaltyController::class, 'index'])->name('index');
        Route::get('/{user}', [LoyaltyController::class, 'show'])->name('show');
        Route::post('/{user}/adjust', [LoyaltyController::class, 'adjustPoints'])->name('adjust');
        Route::get('/settings/tiers', [LoyaltyController::class, 'tierSettings'])->name('settings');
        Route::put('/settings/tiers', [LoyaltyController::class, 'updateTierSettings'])->name('settings.update');
        Route::get('/export/report', [LoyaltyController::class, 'exportLoyaltyReport'])->name('export');
    });
});
```

---

## Phase 4: Client Controllers & Routes

### 4.1 Client Coupon Controller
**File**: `app/Http/Controllers/Client/CouponController.php`

```php
class CouponController extends Controller
{
    public function index()  // View available coupons
    public function validateCode(Request $request) // AJAX: Validate coupon code
    public function applyCoupon(Request $request, ServiceRequest $serviceRequest)
    public function removeCoupon(ServiceRequest $serviceRequest)
}
```

### 4.2 Client Loyalty Controller
**File**: `app/Http/Controllers/Client/LoyaltyController.php`

```php
class LoyaltyController extends Controller
{
    public function dashboard()          // View points, tier, history
    public function transactions()       // View transaction log
    public function redeemPoints(Request $request, ServiceRequest $serviceRequest)
}
```

### 4.3 Update ServiceRequestController
**File**: `app/Http/Controllers/Client/ServiceRequestController.php`

**Modify `showPayment()` method:**
```php
public function showPayment($id)
{
    $serviceRequest = ServiceRequest::with(['appliedCoupon', 'client.loyaltyPoints'])
        ->where('id', $id)
        ->where('client_id', Auth::id())
        ->firstOrFail();
    
    $availableCoupons = app(CouponService::class)->getAvailableCouponsForUser(Auth::user());
    $loyaltyPoints = Auth::user()->loyaltyPoints;
    
    return view('client.requests.payment', compact('serviceRequest', 'availableCoupons', 'loyaltyPoints'));
}
```

### 4.4 Update MayaPaymentController
**File**: `app/Http/Controllers/Client/MayaPaymentController.php`

**Modify `success()` method to award loyalty points:**
```php
public function success(Request $request)
{
    // ... existing payment confirmation logic
    
    // Award loyalty points after successful payment
    $loyaltyService = app(LoyaltyService::class);
    $loyaltyService->awardPointsForCompletion($serviceRequest);
    
    // ... rest of logic
}
```

### 4.5 Client Routes Addition
**File**: `routes/web.php`

```php
// Client Coupon & Loyalty Routes
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    // Coupons
    Route::get('coupons', [Client\CouponController::class, 'index'])->name('coupons.index');
    Route::post('coupons/validate', [Client\CouponController::class, 'validateCode'])->name('coupons.validate');
    Route::post('requests/{request}/apply-coupon', [Client\CouponController::class, 'applyCoupon'])->name('requests.apply-coupon');
    Route::delete('requests/{request}/remove-coupon', [Client\CouponController::class, 'removeCoupon'])->name('requests.remove-coupon');
    
    // Loyalty
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [Client\LoyaltyController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [Client\LoyaltyController::class, 'transactions'])->name('transactions');
        Route::post('/redeem/{request}', [Client\LoyaltyController::class, 'redeemPoints'])->name('redeem');
    });
});
```

---

## Phase 5: Frontend Views

### 5.1 Admin Views

#### `resources/views/admin/coupons/index.blade.php`
- DataTable with filters (status, type, date range)
- Quick stats cards (total coupons, active, usage rate)
- Bulk actions (activate, deactivate, delete)
- Search by code/name

#### `resources/views/admin/coupons/create.blade.php`
- Form with sections:
  - Basic Info (code, name, description)
  - Discount Settings (type, value, max/min amounts)
  - Visibility & Target (public/user-specific/request-specific)
  - Usage Limits
  - Validity Period
  - Status
- Real-time code availability check (AJAX)

#### `resources/views/admin/coupons/show.blade.php`
- Coupon details card
- Usage statistics (charts)
- Recent usage log table
- QR code generation option

#### `resources/views/admin/requests/show.blade.php` (Modify Approval Modal)
Add section in approval modal:
```blade
<!-- Coupon Assignment Section -->
<div class="mt-6 p-4 border rounded-lg">
    <h4 class="font-semibold mb-3">Attach Coupon (Optional)</h4>
    
    <div x-data="{ attachCoupon: false, createNew: false }">
        <label class="flex items-center mb-3">
            <input type="checkbox" x-model="attachCoupon" name="attach_coupon" class="mr-2">
            <span>Include a discount coupon with this approval</span>
        </label>
        
        <div x-show="attachCoupon" class="ml-6 space-y-3">
            <!-- Option 1: Select Existing -->
            <label class="flex items-center">
                <input type="radio" x-model="createNew" value="false" name="coupon_option" class="mr-2">
                <span>Select existing coupon</span>
            </label>
            <select x-show="!createNew" name="coupon_id" class="form-select w-full">
                <option value="">Choose a coupon...</option>
                @foreach($availableCoupons as $coupon)
                    <option value="{{ $coupon->id }}">
                        {{ $coupon->code }} - {{ $coupon->name }} 
                        ({{ $coupon->discount_type === 'percentage' ? $coupon->discount_value.'%' : '₱'.$coupon->discount_value }})
                    </option>
                @endforeach
            </select>
            
            <!-- Option 2: Create New -->
            <label class="flex items-center">
                <input type="radio" x-model="createNew" value="true" name="coupon_option" class="mr-2">
                <span>Create request-specific coupon</span>
            </label>
            
            <div x-show="createNew" class="bg-gray-50 p-4 rounded space-y-3">
                <input type="text" name="new_coupon_data[code]" placeholder="Coupon Code" class="form-input w-full">
                <input type="text" name="new_coupon_data[name]" placeholder="Display Name" class="form-input w-full">
                <select name="new_coupon_data[discount_type]" class="form-select w-full">
                    <option value="percentage">Percentage</option>
                    <option value="fixed_amount">Fixed Amount</option>
                </select>
                <input type="number" name="new_coupon_data[discount_value]" placeholder="Discount Value" class="form-input w-full">
                <input type="date" name="new_coupon_data[valid_until]" class="form-input w-full">
            </div>
        </div>
    </div>
</div>
```

#### `resources/views/admin/loyalty/index.blade.php`
- User loyalty leaderboard
- Tier distribution chart
- Points statistics
- Search & filter users

#### `resources/views/admin/loyalty/show.blade.php`
- User's loyalty profile
- Points balance & history
- Transaction log table
- Manual adjustment form
- Tier progression chart

---

### 5.2 Client Views

#### `resources/views/client/coupons/index.blade.php`
- Grid/list of available coupons
- Filter by status (available, used, expired)
- Each coupon card shows:
  - Code (with copy button)
  - Discount details
  - Validity dates
  - Terms & conditions
  - "Apply to Request" button

#### `resources/views/client/requests/show.blade.php` (Modify)
Add coupon display section:
```blade
@if($request->status === 'approved' || $request->status === 'pending_payment')
    <div class="glass-card p-6 mb-6 bg-gradient-to-r from-success-50 to-primary-50">
        <h3 class="text-xl font-bold text-success-700 mb-4">
            <i class="fas fa-gift mr-2"></i>Special Discount Applied!
        </h3>
        
        @if($request->appliedCoupon)
            <div class="bg-white rounded-lg p-4 border-2 border-success-300">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-neutral-600 mb-1">Coupon Code</div>
                        <div class="font-mono font-bold text-2xl text-primary-600">{{ $request->appliedCoupon->code }}</div>
                        <div class="text-sm text-neutral-500 mt-1">{{ $request->appliedCoupon->name }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-neutral-600 mb-1">You Save</div>
                        <div class="text-3xl font-bold text-success-600">₱{{ number_format($request->coupon_discount_amount, 0) }}</div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-neutral-200">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-neutral-600">Original Amount:</span>
                        <span class="font-semibold text-neutral-700">₱{{ number_format($request->original_approved_budget, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-success-600">Discount:</span>
                        <span class="font-semibold text-success-600">-₱{{ number_format($request->coupon_discount_amount, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-lg border-t pt-2">
                        <span class="font-bold text-neutral-800">Final Amount:</span>
                        <span class="font-bold text-primary-600">₱{{ number_format($request->approved_budget, 0) }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
```

#### `resources/views/client/loyalty/dashboard.blade.php`
- Points balance card (prominent display)
- Current tier badge with progress bar
- Benefits of current tier
- Points expiring soon alert
- Recent transactions list
- Points earning opportunities
- Referral program section

#### `resources/views/client/requests/payment.blade.php` (New or Modify)
Enhanced payment page with:
- Original amount display
- Applied coupon details (if any)
- Available coupons dropdown (if none applied)
- Loyalty points redemption section:
  ```blade
  <div class="glass-card p-6">
      <h3 class="font-bold text-lg mb-4">Use Loyalty Points</h3>
      <div class="flex items-center justify-between mb-4">
          <span>Available Points:</span>
          <span class="font-bold text-primary-600">{{ $loyaltyPoints->available_points }} pts</span>
      </div>
      <div class="flex items-center justify-between mb-4">
          <span>Points Value:</span>
          <span class="font-bold text-success-600">₱{{ number_format($loyaltyPoints->available_points) }}</span>
      </div>
      <input type="number" max="{{ $loyaltyPoints->available_points }}" 
             placeholder="Points to redeem" 
             class="form-input w-full mb-3"
             x-model="pointsToRedeem">
      <button class="btn btn-primary w-full">Apply Points</button>
  </div>
  ```
- Final amount calculation with breakdown
- Proceed to Maya payment button

---

## Phase 6: Business Logic & Rules

### 6.1 Coupon Validation Rules

```php
// CouponService::validateCoupon()
1. Check if coupon exists and is active
2. Check if current date is within validity period
3. Check if coupon has remaining uses (global)
4. Check if user hasn't exceeded per-user limit
5. Check if coupon is visible to user (public/user-specific/request-specific)
6. Check if purchase amount meets minimum requirement
7. Return validation result with discount calculation
```

### 6.2 Loyalty Points Earning Rules

```php
// LoyaltyService::calculatePointsForPayment()
Base Rate by Tier:
- Bronze: 1 point per ₱100 spent (1% return)
- Silver: 2 points per ₱100 spent (2% return)
- Gold: 3 points per ₱100 spent (3% return)
- Platinum: 5 points per ₱100 spent (5% return)

Bonus Points:
- First project completion: +500 bonus points
- Referral (when referred client completes first payment): +1000 points
- Milestone completion: +200 points per milestone
- Project completion: +500 points
- Review/Feedback submission: +100 points
- Anniversary bonus (yearly): +1000 points

Points Expiry:
- Points expire after 12 months from earning date
- Warning notification 30 days before expiry
```

### 6.3 Loyalty Tier Thresholds

```php
Tier Requirements (Lifetime Earned Points):
- Bronze: 0 - 4,999 points (default)
- Silver: 5,000 - 14,999 points
- Gold: 15,000 - 49,999 points
- Platinum: 50,000+ points

Tier Benefits:
Bronze:
- 1% points earning rate
- Access to basic coupons

Silver:
- 2% points earning rate
- 5% discount on all services
- Priority support (response within 24h)
- Early access to new services

Gold:
- 3% points earning rate
- 10% discount on all services
- Priority support (response within 12h)
- Free minor revisions (1 per project)
- Birthday month special coupon

Platinum:
- 5% points earning rate
- 15% discount on all services
- VIP support (response within 6h)
- Free minor revisions (2 per project)
- Quarterly exclusive coupons
- Dedicated account manager
- Free consultation sessions
```

### 6.4 Points Redemption Rules

```php
// LoyaltyService::convertPointsToDiscount()
Conversion Rate: 1 point = ₱1 discount

Restrictions:
- Minimum redemption: 100 points
- Maximum redemption per transaction: 50% of order value
- Cannot combine with certain coupons (defined per coupon)
- Points are deducted immediately upon redemption
- If payment fails, points are refunded
```

### 6.5 Discount Stacking Rules

```php
Priority Order (applied in sequence):
1. Coupon discount (percentage or fixed)
2. Loyalty tier discount (if no coupon applied, or if stackable)
3. Loyalty points redemption (applied to already-discounted amount)

Stacking Restrictions:
- Only ONE coupon can be applied per transaction
- Loyalty tier discount: Automatic, but disabled if non-stackable coupon used
- Loyalty points: Can be used with most discounts
- Maximum total discount: 70% of original amount (safety cap)
```

---

## Phase 7: Email Notifications

### 7.1 New Mail Classes

**File**: `app/Mail/CouponAssignedMail.php`
- Sent when admin assigns coupon to approved request
- Contains: Coupon code, discount details, expiry date, usage instructions

**File**: `app/Mail/LoyaltyPointsEarnedMail.php`
- Sent after payment confirmation
- Contains: Points earned, new balance, current tier, points to next tier

**File**: `app/Mail/TierUpgradedMail.php`
- Sent when user reaches new tier
- Contains: New tier name, benefits unlocked, congratulations message

**File**: `app/Mail/PointsExpiringMail.php`
- Sent 30 days before points expire
- Contains: Points expiring, expiry date, suggestion to redeem

**File**: `app/Mail/CouponExpiringMail.php`
- Sent 7 days before user-specific coupon expires
- Contains: Coupon details, expiry date, call-to-action

### 7.2 Email Views

```blade
resources/views/emails/
    ├── coupon-assigned.blade.php
    ├── loyalty-points-earned.blade.php
    ├── tier-upgraded.blade.php
    ├── points-expiring.blade.php
    └── coupon-expiring.blade.php
```

---

## Phase 8: API Endpoints (Optional - for SPA/Mobile)

```php
Route::prefix('api/v1')->middleware('auth:sanctum')->group(function () {
    // Coupons
    Route::get('coupons/available', [Api\CouponController::class, 'available']);
    Route::post('coupons/validate', [Api\CouponController::class, 'validate']);
    
    // Loyalty
    Route::get('loyalty/balance', [Api\LoyaltyController::class, 'balance']);
    Route::get('loyalty/transactions', [Api\LoyaltyController::class, 'transactions']);
    Route::post('loyalty/redeem', [Api\LoyaltyController::class, 'redeem']);
});
```

---

## Phase 9: Testing Strategy

### 9.1 Unit Tests

```php
tests/Unit/
    ├── CouponTest.php
    │   ├── testCouponValidation()
    │   ├── testDiscountCalculation()
    │   ├── testUsageLimits()
    │   └── testExpiration()
    ├── LoyaltyPointTest.php
    │   ├── testPointsEarning()
    │   ├── testPointsRedemption()
    │   ├── testTierCalculation()
    │   └── testPointsExpiry()
    └── DiscountStackingTest.php
        ├── testCouponOnly()
        ├── testLoyaltyOnly()
        ├── testStackedDiscounts()
        └── testMaximumDiscount()
```

### 9.2 Feature Tests

```php
tests/Feature/
    ├── AdminCouponManagementTest.php
    ├── ClientCouponUsageTest.php
    ├── LoyaltySystemTest.php
    └── PaymentWithDiscountsTest.php
```

### 9.3 Manual Testing Scenarios

1. **Coupon Flow:**
   - Admin creates public coupon
   - Client views and applies coupon
   - Client proceeds to payment with discount
   - Verify discount reflected in Maya payment
   - Verify coupon usage tracked

2. **Loyalty Flow:**
   - New client completes first payment
   - Verify points awarded correctly
   - Client accumulates points across multiple projects
   - Verify tier upgrade at threshold
   - Client redeems points for discount
   - Verify points deducted correctly

3. **Edge Cases:**
   - Expired coupon rejection
   - Usage limit exceeded
   - Invalid coupon code
   - Points insufficient for redemption
   - Maximum discount cap enforcement
   - Payment failure (verify points refund)

---

## Phase 10: Configuration & Seeder

### 10.1 Configuration File
**File**: `config/loyalty.php`

```php
return [
    'points' => [
        'earning_rate' => [
            'bronze' => 1,   // 1% return
            'silver' => 2,   // 2% return
            'gold' => 3,     // 3% return
            'platinum' => 5, // 5% return
        ],
        'conversion_rate' => 1, // 1 point = ₱1
        'minimum_redemption' => 100,
        'maximum_redemption_percentage' => 50,
        'expiry_months' => 12,
        'expiry_warning_days' => 30,
    ],
    
    'tiers' => [
        'bronze' => ['points' => 0, 'discount' => 0],
        'silver' => ['points' => 5000, 'discount' => 5],
        'gold' => ['points' => 15000, 'discount' => 10],
        'platinum' => ['points' => 50000, 'discount' => 15],
    ],
    
    'bonuses' => [
        'first_project' => 500,
        'referral' => 1000,
        'milestone_completion' => 200,
        'project_completion' => 500,
        'feedback_submission' => 100,
        'anniversary' => 1000,
    ],
    
    'discounts' => [
        'maximum_total_percentage' => 70,
        'stackable_by_default' => false,
    ],
];
```

### 10.2 Database Seeder
**File**: `database/seeders/CouponLoyaltySeeder.php`

```php
// Seed sample coupons
Coupon::create([
    'code' => 'WELCOME2025',
    'name' => 'Welcome Discount',
    'discount_type' => 'percentage',
    'discount_value' => 10,
    'coupon_type' => 'public',
    'status' => 'active',
    'created_by' => 1,
]);

// Seed loyalty tiers
LoyaltyTier::create([
    'tier' => 'bronze',
    'points_required' => 0,
    'discount_percentage' => 0,
    'benefits' => ['Basic support', 'Standard processing'],
]);

// Initialize loyalty points for existing clients
User::where('role', 'client')->each(function ($user) {
    LoyaltyPoint::create([
        'user_id' => $user->id,
        'total_points' => 0,
        'tier' => 'bronze',
    ]);
});
```

---

## Phase 11: Admin Dashboard Widgets

### 11.1 Coupon Analytics Widget
- Total active coupons
- Total redemptions this month
- Most popular coupon
- Average discount given

### 11.2 Loyalty Analytics Widget
- Total active loyalty members
- Tier distribution chart
- Total points in circulation
- Average points per user

---

## Phase 12: Scheduled Tasks

**File**: `app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule)
{
    // Expire old coupons daily
    $schedule->call(function () {
        Coupon::where('status', 'active')
            ->where('valid_until', '<', now())
            ->update(['status' => 'expired']);
    })->daily();
    
    // Send points expiry warnings
    $schedule->call(function () {
        $expiringDate = now()->addDays(config('loyalty.points.expiry_warning_days'));
        
        LoyaltyTransaction::where('transaction_type', 'earned')
            ->where('expires_at', '<=', $expiringDate)
            ->where('expires_at', '>', now())
            ->whereNull('expiry_warning_sent')
            ->chunk(100, function ($transactions) {
                // Send warning emails
            });
    })->daily();
    
    // Expire old loyalty points
    $schedule->call(function () {
        $expiredTransactions = LoyaltyTransaction::where('transaction_type', 'earned')
            ->where('expires_at', '<', now())
            ->whereNull('expired_at')
            ->get();
        
        foreach ($expiredTransactions as $transaction) {
            // Deduct expired points and log
        }
    })->daily();
}
```

---

## Phase 13: Audit & Security

### 13.1 Audit Logging
- All coupon creation/modification
- All loyalty point adjustments
- All redemptions and applications
- Failed validation attempts (potential abuse detection)

### 13.2 Security Measures
- Rate limiting on coupon validation endpoint (prevent brute force)
- CSRF protection on all forms
- Authorization checks (users can only view their own coupons/points)
- Admin-only access to sensitive operations
- Input sanitization and validation
- SQL injection prevention (Eloquent ORM)

---

## Phase 14: Documentation

### 14.1 Admin Guide
- How to create coupons
- How to assign coupons to requests
- How to manage loyalty program
- How to view analytics and reports

### 14.2 Client Guide
- How to view available coupons
- How to apply coupons at checkout
- How to earn loyalty points
- How to redeem loyalty points
- Understanding tiers and benefits

### 14.3 Developer Documentation
- API documentation
- Database schema documentation
- Service layer documentation
- Testing documentation

---

## 📊 IMPLEMENTATION TIMELINE

### Week 1: Foundation
- ✅ Database migrations
- ✅ Models and relationships
- ✅ Service layers (CouponService, LoyaltyService)
- ✅ Configuration files

### Week 2: Admin Features
- ✅ Admin coupon management (CRUD)
- ✅ Admin loyalty management
- ✅ Request approval with coupon assignment
- ✅ Admin views and forms

### Week 3: Client Features
- ✅ Client coupon browsing
- ✅ Client loyalty dashboard
- ✅ Payment page integration
- ✅ Coupon application flow
- ✅ Points redemption flow

### Week 4: Integration & Polish
- ✅ Maya payment integration updates
- ✅ Email notifications
- ✅ Scheduled tasks
- ✅ Testing (unit + feature)
- ✅ Bug fixes and refinement

### Week 5: Testing & Deployment
- ✅ End-to-end testing
- ✅ User acceptance testing
- ✅ Documentation
- ✅ Deployment to production
- ✅ Monitoring and support

---

## 🎯 SUCCESS METRICS

### Technical Metrics
- All tests passing (100% coverage for critical paths)
- Page load time < 2 seconds
- API response time < 500ms
- Zero critical security vulnerabilities

### Business Metrics
- Coupon redemption rate > 30%
- Average discount per transaction: 5-15%
- Loyalty program enrollment rate > 80% of clients
- Tier progression rate (users moving up tiers)
- Customer retention improvement (to be measured)

---

## 🚀 FUTURE ENHANCEMENTS (Phase 2)

1. **Referral System**
   - Generate unique referral codes
   - Track referrals and reward both parties
   - Tiered referral rewards

2. **Gamification**
   - Badges and achievements
   - Leaderboards
   - Challenges and missions

3. **Advanced Coupons**
   - Bundle coupons (multiple services)
   - BOGO (Buy One Get One)
   - Tiered discounts (spend more, save more)
   - Combo deals

4. **Loyalty Marketplace**
   - Redeem points for physical rewards
   - Partner perks and benefits
   - Gift cards and vouchers

5. **Mobile App Integration**
   - Push notifications for deals
   - QR code scanning
   - Mobile-exclusive coupons

6. **Analytics Dashboard**
   - Coupon performance analytics
   - Customer lifetime value analysis
   - Cohort analysis
   - Predictive analytics (churn prediction)

7. **Automated Marketing**
   - Win-back campaigns (inactive users)
   - Personalized coupon generation
   - Dynamic pricing based on user behavior
   - A/B testing for coupons

---

## 📝 NOTES & BEST PRACTICES

### Coupon Code Generation
- Use alphanumeric codes (uppercase)
- Avoid ambiguous characters (0, O, I, 1, l)
- Length: 6-12 characters
- Pattern examples: `SUMMER2025`, `LOYAL-50-XYZ`, `FIRST10`

### Discount Calculation Order
```php
1. Start with original approved budget
2. Apply coupon discount
3. Apply loyalty tier discount (if stackable)
4. Apply loyalty points redemption
5. Enforce maximum discount cap
6. Ensure final amount >= minimum allowed (e.g., ₱100)
```

### Edge Case Handling
- Payment failure: Refund redeemed points immediately
- Request cancellation: Release coupon for reuse (if not fully consumed)
- Multiple simultaneous applications: Use database transactions with locks
- Timezone considerations: Use UTC for all timestamps, display in user timezone

### Performance Optimization
- Cache active coupons for public display
- Index frequently queried columns
- Paginate long lists (transactions, coupons)
- Lazy load relationships in controllers
- Use eager loading to prevent N+1 queries

---

## ✅ PRE-DEPLOYMENT CHECKLIST

- [ ] All migrations tested on staging
- [ ] Seeders populate sample data correctly
- [ ] All unit tests passing
- [ ] All feature tests passing
- [ ] Manual testing completed for all user flows
- [ ] Edge cases handled gracefully
- [ ] Error messages user-friendly
- [ ] Email templates reviewed and tested
- [ ] Admin documentation completed
- [ ] Client FAQ updated
- [ ] Database backup created
- [ ] Rollback plan documented
- [ ] Monitoring alerts configured
- [ ] Performance benchmarks met
- [ ] Security audit completed
- [ ] Code review approved

---

## 🆘 TROUBLESHOOTING GUIDE

### Common Issues

**Issue**: Coupon not applying at checkout
- Check coupon status (active?)
- Verify validity dates
- Check user eligibility
- Verify minimum purchase met
- Check usage limits

**Issue**: Loyalty points not awarded after payment
- Verify payment status (confirmed?)
- Check payment webhook received
- Review job queue (is it running?)
- Check user has loyalty account initialized

**Issue**: Discount calculation incorrect
- Verify discount stacking rules
- Check maximum discount cap
- Review calculation order
- Test with isolated scenarios

---

## 📞 SUPPORT & MAINTENANCE

### Ongoing Tasks
- Monitor coupon usage patterns
- Review and adjust tier thresholds quarterly
- Clean up expired coupons and points
- Analyze customer feedback
- Optimize database queries
- Update documentation

### Regular Reports
- Monthly: Coupon usage report
- Monthly: Loyalty program health metrics
- Quarterly: Customer retention analysis
- Annually: ROI analysis of loyalty program

---

**End of Implementation Plan**

This comprehensive plan covers all aspects of implementing a robust Coupon and Loyalty System integrated seamlessly with your existing Laravel CMS. The system is designed to be scalable, secure, and user-friendly for both admins and clients.
