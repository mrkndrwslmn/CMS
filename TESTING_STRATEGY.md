# CMS Testing Strategy & Implementation Guide

**Document Version:** 1.0  
**Last Updated:** December 2, 2025  
**Project:** CMS (Content Management System)

---

## Table of Contents

1. [Overview](#1-overview)
2. [Testing Approaches](#2-testing-approaches)
3. [Testing Tools & Configuration](#3-testing-tools--configuration)
4. [Test Categories & Priorities](#4-test-categories--priorities)
5. [Sample Test Cases](#5-sample-test-cases)
6. [Mock Services for External Integrations](#6-mock-services-for-external-integrations)
7. [Debugging Tools](#7-debugging-tools)
8. [Test Execution & Results](#8-test-execution--results)
9. [CI/CD Integration](#9-cicd-integration)
10. [Test Coverage Goals](#10-test-coverage-goals)

---

## 1. Overview

This document describes the comprehensive testing strategy for the CMS Laravel application. The goal is to ensure all system functionalities work correctly, identify issues early, and maintain code quality through automated testing.

### 1.1 System Components

The CMS includes the following major components requiring testing:

| Component | Description | Priority |
|-----------|-------------|----------|
| **Payment Processing** | Maya payment gateway integration | 🔴 Critical |
| **Payout Management** | Time entry approval, wallet transactions | 🔴 Critical |
| **Time Tracking** | Start/stop timer, duration calculation | 🔴 Critical |
| **Wallet System** | Earnings, credits, withdrawals | 🔴 Critical |
| **Service Requests** | Request submission, approval workflow | 🟠 High |
| **Project Management** | Assignment, status transitions | 🟠 High |
| **User Authentication** | Login, registration, roles | 🟠 High |
| **Referral System** | Code generation, rewards | 🟠 High |
| **Coupon System** | Validation, application | 🟡 Medium |
| **Loyalty Program** | Points, redemption, tiers | 🟡 Medium |
| **Notifications** | Email, push, in-app | 🟡 Medium |
| **Messaging** | Conversations, group chats | 🟢 Lower |
| **Document Management** | Upload, download | 🟢 Lower |

---

## 2. Testing Approaches

### 2.1 Unit Testing

**Purpose:** Test individual components (models, services, helpers) in isolation.

**Characteristics:**
- Fast execution (milliseconds per test)
- No database or external service dependencies
- Mock all dependencies
- Focus on single method/function behavior

**When to Use:**
- Testing model methods (calculations, attribute accessors)
- Testing service class business logic
- Testing helper functions and utilities
- Testing validation rules

**Example Locations:**
```
tests/Unit/
├── Models/
│   ├── TimeEntryTest.php
│   ├── PayoutTest.php
│   ├── UserWalletTest.php
│   └── ...
├── Services/
│   ├── LoyaltyServiceTest.php
│   ├── CouponServiceTest.php
│   └── ReferralServiceTest.php
└── Helpers/
    └── ...
```

### 2.2 Feature Testing (Integration Testing)

**Purpose:** Test complete HTTP request/response cycles and feature workflows.

**Characteristics:**
- Uses real database (SQLite in-memory for speed)
- Tests controller actions, middleware, and responses
- Validates complete workflows end-to-end
- Uses factories to create test data

**When to Use:**
- Testing API endpoints
- Testing form submissions
- Testing authentication flows
- Testing complete business workflows

**Example Locations:**
```
tests/Feature/
├── Admin/
│   ├── PayoutManagementTest.php
│   ├── ProjectManagementTest.php
│   └── UserManagementTest.php
├── Adiutor/
│   ├── TimeTrackingTest.php
│   ├── EarningsTest.php
│   └── ProjectTest.php
├── Client/
│   ├── ServiceRequestTest.php
│   ├── PaymentTest.php
│   └── ReferralTest.php
└── Auth/
    ├── LoginTest.php
    └── RegistrationTest.php
```

### 2.3 Manual Testing

**Purpose:** Exploratory testing for UI/UX and edge cases.

**When to Use:**
- Testing visual elements and layouts
- Testing complex user interactions
- Exploratory testing for edge cases
- Usability testing

**Recommended Approach:**
1. Create test scenarios document
2. Execute manually before releases
3. Document findings in issue tracker
4. Convert repeated tests to automated tests

### 2.4 Browser Testing (E2E)

**Purpose:** Simulate real browser interactions using Laravel Dusk.

**When to Use:**
- Testing JavaScript-heavy features
- Testing multi-step user journeys
- Testing real payment flows in sandbox
- Cross-browser compatibility testing

---

## 3. Testing Tools & Configuration

### 3.1 Primary Testing Framework: PHPUnit

**Already Configured:** `phpunit.xml`

```xml
<!-- Key Configuration -->
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
    <env name="MAIL_MAILER" value="array"/>
    <env name="QUEUE_CONNECTION" value="sync"/>
</php>
```

**Running Tests:**
```bash
# Run all tests
php artisan test

# Run with coverage report
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/Admin/PayoutManagementTest.php

# Run specific test method
php artisan test --filter=test_can_approve_time_entry

# Run tests in parallel (faster)
php artisan test --parallel
```

### 3.2 Installed Testing Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `phpunit/phpunit` | ^11.5.3 | Core testing framework |
| `mockery/mockery` | ^1.6 | Mocking library |
| `fakerphp/faker` | ^1.23 | Test data generation |

### 3.3 Recommended Additional Tools

#### Xdebug (Debugging)

**Installation (Windows with XAMPP/Laragon):**
1. Download Xdebug DLL from https://xdebug.org/wizard
2. Add to php.ini:
```ini
[xdebug]
zend_extension=xdebug
xdebug.mode=debug,coverage
xdebug.start_with_request=yes
xdebug.client_port=9003
xdebug.client_host=127.0.0.1
xdebug.idekey=VSCODE
```

**VS Code Configuration (.vscode/launch.json):**
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003
        },
        {
            "name": "PHPUnit Debug",
            "type": "php",
            "request": "launch",
            "program": "${workspaceFolder}/vendor/bin/phpunit",
            "args": ["${file}"],
            "cwd": "${workspaceFolder}",
            "port": 9003
        }
    ]
}
```

#### Laravel Telescope (Request/Query Monitoring)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

#### Laravel Dusk (Browser Testing)

```bash
composer require laravel/dusk --dev
php artisan dusk:install
```

---

## 4. Test Categories & Priorities

### 4.1 Priority 1: Financial/Payment Tests (Critical)

| Test Area | Test Cases | Status |
|-----------|------------|--------|
| **Maya Payment** | Checkout creation, verification, success/failure handling | ⬜ |
| **Payout Approval** | Time entry approval, adjustment, wallet credit | ⬜ |
| **Payout Completion** | Complete payout, mark entries paid, notifications | ⬜ |
| **Payout Cancellation** | Cancel payout, reason validation, notifications | ⬜ |
| **Time Entry Calculation** | Duration, hourly rate, max hours capping | ⬜ |
| **Wallet Transactions** | Credit earnings, debit withdrawals, balance | ⬜ |

### 4.2 Priority 2: Core Business Tests (High)

| Test Area | Test Cases | Status |
|-----------|------------|--------|
| **Service Request** | Create, approve, reject, payment confirmation | ⬜ |
| **Project Workflow** | Create from request, assign adiutor, status transitions | ⬜ |
| **Task Management** | Create, assign, update status, budget tracking | ⬜ |
| **User Authentication** | Login, logout, registration, password reset | ⬜ |
| **Role Authorization** | Admin access, adiutor access, client access | ⬜ |
| **Referral System** | Code generation, apply code, credit rewards | ⬜ |

### 4.3 Priority 3: Feature Tests (Medium)

| Test Area | Test Cases | Status |
|-----------|------------|--------|
| **Coupon System** | Validate code, apply discount, usage limits | ⬜ |
| **Loyalty Program** | Earn points, redeem, tier upgrades | ⬜ |
| **Revision Requests** | Submit, approve, complete | ⬜ |
| **Notifications** | Email sending, push notifications, in-app | ⬜ |
| **Hour Increase** | Request, approve, update limits | ⬜ |

### 4.4 Priority 4: Secondary Features (Lower)

| Test Area | Test Cases | Status |
|-----------|------------|--------|
| **Messaging** | Send, receive, mark read | ⬜ |
| **Documents** | Upload, download, delete | ⬜ |
| **Meetings** | Schedule, approve, Zoom integration | ⬜ |
| **Calendar** | Google Calendar sync | ⬜ |
| **Reports** | Generate, export | ⬜ |

---

## 5. Sample Test Cases

### 5.1 Unit Test: TimeEntry Duration Calculation

```php
<?php

namespace Tests\Unit\Models;

use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_duration_correctly()
    {
        $timeEntry = TimeEntry::factory()->create([
            'start_time' => now()->subHours(2),
            'end_time' => now(),
            'hourly_rate' => 500,
        ]);

        $this->assertEquals(120, $timeEntry->duration_minutes);
        $this->assertEquals(1000, $timeEntry->calculated_amount); // 2 hours * 500
    }

    public function test_caps_billable_hours_at_maximum()
    {
        $timeEntry = TimeEntry::factory()->create([
            'start_time' => now()->subHours(10),
            'end_time' => now(),
            'hourly_rate' => 100,
            'max_billable_hours' => 8,
        ]);

        $this->assertEquals(600, $timeEntry->duration_minutes); // 10 hours = 600 mins
        $this->assertEquals(480, $timeEntry->billable_minutes);  // Capped at 8 hours
        $this->assertEquals(800, $timeEntry->calculated_amount); // 8 * 100
        $this->assertTrue($timeEntry->is_capped);
    }

    public function test_formats_duration_correctly()
    {
        $timeEntry = TimeEntry::factory()->create([
            'start_time' => now()->subMinutes(150), // 2.5 hours
            'end_time' => now(),
        ]);

        $this->assertEquals('2h 30m', $timeEntry->getFormattedDuration());
    }
}
```

### 5.2 Feature Test: Payout Approval Workflow

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Payout;
use App\Models\TimeEntry;
use App\Mail\PayoutPaidMail;
use App\Notifications\PayoutPaidNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PayoutManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $adiutor;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->admin()->create();
        $this->adiutor = User::factory()->adiutor()->create();
    }

    public function test_admin_can_view_payouts_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payouts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.payouts.index');
    }

    public function test_non_admin_cannot_access_payouts()
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)
            ->get(route('admin.payouts.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_complete_payout()
    {
        Mail::fake();
        Notification::fake();

        $payout = Payout::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'status' => 'pending',
            'amount' => 5000,
        ]);

        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'payout_id' => $payout->id,
            'is_paid' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.complete', $payout->id), [
                'reference_number' => 'REF-123456',
                'notes' => 'Payment processed via bank transfer',
            ]);

        $response->assertRedirect(route('admin.payouts.show', $payout->id));
        
        $payout->refresh();
        $this->assertEquals('completed', $payout->status);
        $this->assertEquals('REF-123456', $payout->reference_number);
        $this->assertNotNull($payout->completed_at);

        $timeEntry->refresh();
        $this->assertTrue($timeEntry->is_paid);

        // Verify notifications were sent
        Mail::assertSent(PayoutPaidMail::class, function ($mail) {
            return $mail->hasTo($this->adiutor->email);
        });

        Notification::assertSentTo($this->adiutor, PayoutPaidNotification::class);
    }

    public function test_cannot_complete_already_completed_payout()
    {
        $payout = Payout::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.complete', $payout->id), [
                'reference_number' => 'REF-123456',
            ]);

        $response->assertSessionHasErrors('error');
    }

    public function test_admin_can_cancel_pending_payout()
    {
        Mail::fake();
        Notification::fake();

        $payout = Payout::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.cancel', $payout->id), [
                'reason' => 'Invalid bank details provided',
            ]);

        $response->assertRedirect(route('admin.payouts.show', $payout->id));

        $payout->refresh();
        $this->assertEquals('cancelled', $payout->status);
    }

    public function test_cannot_cancel_completed_payout()
    {
        $payout = Payout::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.cancel', $payout->id), [
                'reason' => 'Test cancellation',
            ]);

        $response->assertSessionHasErrors('error');
    }
}
```

### 5.3 Feature Test: Time Entry Approval with Wallet Credit

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\Project;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeEntryApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_time_entry_credits_wallet()
    {
        $admin = User::factory()->admin()->create();
        $adiutor = User::factory()->adiutor()->create([
            'work_earnings_balance' => 0,
        ]);
        
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        
        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $adiutor->id,
            'task_id' => $task->id,
            'project_id' => $project->id,
            'start_time' => now()->subHours(2),
            'end_time' => now(),
            'hourly_rate' => 500,
            'calculated_amount' => 1000,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.time-entries.approve', $timeEntry->id));

        $response->assertSessionHas('success');

        $timeEntry->refresh();
        $this->assertTrue($timeEntry->is_approved);
        $this->assertEquals($admin->id, $timeEntry->approved_by);
        $this->assertNotNull($timeEntry->approved_at);

        $adiutor->refresh();
        $this->assertEquals(1000, $adiutor->work_earnings_balance);

        // Verify wallet transaction was created
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $adiutor->id,
            'amount' => 1000,
            'type' => 'credit',
            'source_type' => WalletTransaction::SOURCE_TIME_ENTRY,
            'source_id' => $timeEntry->id,
        ]);
    }

    public function test_approving_with_adjustment_uses_adjusted_hours()
    {
        $admin = User::factory()->admin()->create();
        $adiutor = User::factory()->adiutor()->create();
        
        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $adiutor->id,
            'start_time' => now()->subHours(5),
            'end_time' => now(),
            'hourly_rate' => 100,
            'calculated_amount' => 500, // Original: 5 hours * 100
            'is_approved' => false,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.time-entries.approve', $timeEntry->id), [
                'adjust' => true,
                'adjusted_hours' => 3,
                'adjustment_reason' => 'Reduced due to break time not logged',
            ]);

        $timeEntry->refresh();
        $this->assertTrue($timeEntry->is_approved);
        $this->assertEquals(300, $timeEntry->calculated_amount); // Adjusted: 3 * 100
        $this->assertNotNull($timeEntry->adjustment_reason);
    }
}
```

### 5.4 Feature Test: Service Request Workflow

```php
<?php

namespace Tests\Feature\Client;

use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_service_request()
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)
            ->post(route('client.service-requests.store'), [
                'service_type' => 'web_development',
                'title' => 'Build E-commerce Website',
                'description' => 'Need a full-featured online store',
                'budget_min' => 50000,
                'budget_max' => 100000,
                'deadline' => now()->addMonths(2)->format('Y-m-d'),
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('service_requests', [
            'client_id' => $client->id,
            'title' => 'Build E-commerce Website',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_service_request()
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->client()->create();
        
        $request = ServiceRequest::factory()->create([
            'client_id' => $client->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.requests.approve', $request->id), [
                'final_price' => 75000,
                'notes' => 'Approved with standard terms',
            ]);

        $response->assertRedirect();

        $request->refresh();
        $this->assertEquals('approved', $request->status);
    }
}
```

### 5.5 Unit Test: User Wallet Methods

```php
<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWalletTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_work_earnings_increases_balance()
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 1000,
        ]);

        $user->addWorkEarnings(
            500,
            WalletTransaction::SOURCE_TIME_ENTRY,
            1,
            'Test earning',
            null,
            []
        );

        $user->refresh();
        $this->assertEquals(1500, $user->work_earnings_balance);
    }

    public function test_add_referral_credits_increases_balance()
    {
        $user = User::factory()->create([
            'referral_credits_balance' => 0,
        ]);

        $user->addReferralCredits(
            100,
            'Referral bonus',
            1,
            []
        );

        $user->refresh();
        $this->assertEquals(100, $user->referral_credits_balance);
    }

    public function test_cannot_withdraw_more_than_balance()
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 500,
        ]);

        $this->expectException(\Exception::class);

        $user->withdrawWorkEarnings(1000, 'Test withdrawal');
    }

    public function test_successful_withdrawal_creates_transaction()
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 1000,
        ]);

        $user->withdrawWorkEarnings(500, 'Payout withdrawal');

        $user->refresh();
        $this->assertEquals(500, $user->work_earnings_balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'amount' => 500,
            'type' => 'debit',
        ]);
    }
}
```

### 5.6 Unit Test: Loyalty Service

```php
<?php

namespace Tests\Unit\Services;

use App\Services\LoyaltyService;
use App\Models\User;
use App\Models\LoyaltyTier;
use App\Models\LoyaltyPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoyaltyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LoyaltyService $loyaltyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loyaltyService = app(LoyaltyService::class);
    }

    public function test_awards_points_for_purchase()
    {
        $user = User::factory()->client()->create();
        $tier = LoyaltyTier::factory()->create([
            'points_multiplier' => 1.0,
        ]);

        $points = $this->loyaltyService->awardPoints(
            $user,
            1000, // Amount spent
            'purchase',
            'Test purchase'
        );

        $this->assertGreaterThan(0, $points);
        $this->assertDatabaseHas('loyalty_transactions', [
            'user_id' => $user->id,
            'type' => 'earn',
        ]);
    }

    public function test_higher_tier_gets_multiplied_points()
    {
        $bronzeTier = LoyaltyTier::factory()->create([
            'name' => 'Bronze',
            'points_multiplier' => 1.0,
        ]);

        $goldTier = LoyaltyTier::factory()->create([
            'name' => 'Gold',
            'points_multiplier' => 2.0,
        ]);

        $bronzeUser = User::factory()->client()->create();
        $goldUser = User::factory()->client()->create();

        // Assign tiers
        LoyaltyPoint::factory()->create([
            'user_id' => $bronzeUser->id,
            'tier_id' => $bronzeTier->id,
        ]);

        LoyaltyPoint::factory()->create([
            'user_id' => $goldUser->id,
            'tier_id' => $goldTier->id,
        ]);

        $bronzePoints = $this->loyaltyService->awardPoints($bronzeUser, 1000, 'purchase', 'Test');
        $goldPoints = $this->loyaltyService->awardPoints($goldUser, 1000, 'purchase', 'Test');

        $this->assertEquals($bronzePoints * 2, $goldPoints);
    }

    public function test_can_redeem_points_for_discount()
    {
        $user = User::factory()->client()->create();
        
        // Give user some points
        LoyaltyPoint::factory()->create([
            'user_id' => $user->id,
            'points' => 1000,
        ]);

        $discount = $this->loyaltyService->redeemPoints($user, 500, 'Discount redemption');

        $this->assertGreaterThan(0, $discount);

        $loyaltyPoint = LoyaltyPoint::where('user_id', $user->id)->first();
        $this->assertEquals(500, $loyaltyPoint->points);
    }

    public function test_cannot_redeem_more_points_than_available()
    {
        $user = User::factory()->client()->create();
        
        LoyaltyPoint::factory()->create([
            'user_id' => $user->id,
            'points' => 100,
        ]);

        $this->expectException(\Exception::class);

        $this->loyaltyService->redeemPoints($user, 500, 'Test');
    }
}
```

---

## 6. Mock Services for External Integrations

### 6.1 Mock Firebase Service

```php
<?php
// tests/Mocks/FakeFirebaseService.php

namespace Tests\Mocks;

use App\Services\FirebaseService;
use App\Models\User;

class FakeFirebaseService extends FirebaseService
{
    public array $sentNotifications = [];

    public function sendToUser(User $user, array $data, array $notification): bool
    {
        $this->sentNotifications[] = [
            'user_id' => $user->id,
            'data' => $data,
            'notification' => $notification,
        ];

        return true;
    }

    public function sendToTopic(string $topic, array $data, array $notification): bool
    {
        $this->sentNotifications[] = [
            'topic' => $topic,
            'data' => $data,
            'notification' => $notification,
        ];

        return true;
    }

    public function assertSentTo(User $user): bool
    {
        return collect($this->sentNotifications)
            ->contains('user_id', $user->id);
    }

    public function assertNothingSent(): bool
    {
        return empty($this->sentNotifications);
    }
}
```

### 6.2 Mock Maya Payment Service

```php
<?php
// tests/Mocks/FakeMayaPaymentService.php

namespace Tests\Mocks;

use App\Services\MayaPaymentService;

class FakeMayaPaymentService extends MayaPaymentService
{
    public bool $shouldSucceed = true;
    public array $createdCheckouts = [];
    public array $verifiedPayments = [];

    public function createCheckout(array $data): array
    {
        $checkoutId = 'mock_checkout_' . uniqid();
        
        $this->createdCheckouts[] = [
            'checkout_id' => $checkoutId,
            'data' => $data,
        ];

        if ($this->shouldSucceed) {
            return [
                'success' => true,
                'checkout_id' => $checkoutId,
                'checkout_url' => 'https://sandbox.maya.ph/checkout/' . $checkoutId,
            ];
        }

        return [
            'success' => false,
            'error' => 'Mock payment failed',
        ];
    }

    public function verifyPayment(string $checkoutId): array
    {
        $this->verifiedPayments[] = $checkoutId;

        if ($this->shouldSucceed) {
            return [
                'success' => true,
                'status' => 'PAYMENT_SUCCESS',
                'amount' => 1000,
                'reference' => 'mock_ref_' . uniqid(),
            ];
        }

        return [
            'success' => false,
            'status' => 'PAYMENT_FAILED',
        ];
    }

    public function setShouldFail(): self
    {
        $this->shouldSucceed = false;
        return $this;
    }
}
```

### 6.3 Binding Mocks in Tests

```php
<?php
// tests/TestCase.php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Mocks\FakeFirebaseService;
use Tests\Mocks\FakeMayaPaymentService;
use App\Services\FirebaseService;
use App\Services\MayaPaymentService;

abstract class TestCase extends BaseTestCase
{
    protected FakeFirebaseService $fakeFirebase;
    protected FakeMayaPaymentService $fakeMayaPayment;

    protected function setUp(): void
    {
        parent::setUp();

        // Bind fake services
        $this->fakeFirebase = new FakeFirebaseService();
        $this->app->instance(FirebaseService::class, $this->fakeFirebase);

        $this->fakeMayaPayment = new FakeMayaPaymentService();
        $this->app->instance(MayaPaymentService::class, $this->fakeMayaPayment);
    }
}
```

---

## 7. Debugging Tools

### 7.1 Xdebug Configuration

**Purpose:** Step-through debugging, code coverage, profiling

**Installation Steps:**
1. Check PHP version: `php -v`
2. Download correct Xdebug DLL from https://xdebug.org/wizard
3. Add to `php.ini`:
```ini
[xdebug]
zend_extension=xdebug
xdebug.mode=debug,coverage
xdebug.start_with_request=yes
xdebug.client_port=9003
xdebug.client_host=127.0.0.1
xdebug.idekey=VSCODE
xdebug.log=C:/path/to/xdebug.log
```

**Verify Installation:**
```bash
php -v
# Should show: with Xdebug v3.x.x
```

### 7.2 Laravel Telescope

**Purpose:** Debug requests, queries, jobs, cache, mail during development

**Installation:**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

**Access:** `http://your-app.test/telescope`

**Features:**
- Request debugging (headers, payload, response)
- Database query logging with timing
- Exception tracking
- Mail preview
- Queue job monitoring
- Cache operations
- Scheduled task monitoring

### 7.3 Laravel Debugbar

**Purpose:** In-page debugging information

**Installation:**
```bash
composer require barryvdh/laravel-debugbar --dev
```

**Features:**
- Query count and timing
- Memory usage
- Route information
- View data
- Session/request data

### 7.4 Ray (by Spatie)

**Purpose:** Modern debugging tool with desktop app

**Installation:**
```bash
composer require spatie/laravel-ray --dev
```

**Usage:**
```php
ray($variable);
ray()->measure();
ray()->showQueries();
```

---

## 8. Test Execution & Results

### 8.1 Running Tests

```bash
# Run all tests
php artisan test

# Run with verbose output
php artisan test --verbose

# Run specific suite
php artisan test --testsuite=Feature

# Run with coverage (requires Xdebug)
php artisan test --coverage

# Run with coverage HTML report
php artisan test --coverage-html=coverage-report

# Run specific test class
php artisan test tests/Feature/Admin/PayoutManagementTest.php

# Run specific test method
php artisan test --filter=test_admin_can_complete_payout

# Run tests in parallel (faster)
php artisan test --parallel

# Stop on first failure
php artisan test --stop-on-failure
```

### 8.2 Expected Test Results Format

```
   PASS  Tests\Unit\Models\TimeEntryTest
  ✓ calculates duration correctly                                    0.05s
  ✓ caps billable hours at maximum                                   0.03s
  ✓ formats duration correctly                                       0.02s

   PASS  Tests\Feature\Admin\PayoutManagementTest
  ✓ admin can view payouts index                                     0.15s
  ✓ non admin cannot access payouts                                  0.08s
  ✓ admin can complete payout                                        0.25s
  ✓ cannot complete already completed payout                         0.12s

  Tests:    7 passed (15 assertions)
  Duration: 0.70s
```

### 8.3 Coverage Report Goals

| Category | Target Coverage |
|----------|-----------------|
| Models (Core) | ≥ 80% |
| Services | ≥ 85% |
| Controllers (Critical) | ≥ 70% |
| Controllers (Other) | ≥ 50% |
| Overall | ≥ 60% |

---

## 9. CI/CD Integration

### 9.1 GitHub Actions Workflow

```yaml
# .github/workflows/tests.yml

name: Tests

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  tests:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: testing
          MYSQL_ROOT_PASSWORD: password
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql, dom, filter, gd, json, mbstring, pdo
          coverage: xdebug

      - name: Install Dependencies
        run: composer install --prefer-dist --no-interaction --no-progress

      - name: Copy .env
        run: cp .env.example .env.testing

      - name: Generate Key
        run: php artisan key:generate --env=testing

      - name: Run Tests
        run: php artisan test --coverage-clover=coverage.xml

      - name: Upload Coverage
        uses: codecov/codecov-action@v3
        with:
          file: ./coverage.xml
```

---

## 10. Test Coverage Goals

### 10.1 Phased Implementation

**Phase 1 (Week 1-2): Critical Financial Tests**
- [ ] TimeEntry model tests
- [ ] Payout model tests
- [ ] User wallet tests
- [ ] PayoutManagementController tests
- [ ] MayaPaymentController tests

**Phase 2 (Week 3-4): Core Business Tests**
- [ ] ServiceRequest workflow tests
- [ ] Project management tests
- [ ] Task management tests
- [ ] Authentication tests
- [ ] Authorization tests

**Phase 3 (Week 5-6): Feature Tests**
- [ ] Referral system tests
- [ ] Coupon system tests
- [ ] Loyalty program tests
- [ ] Notification tests

**Phase 4 (Week 7-8): Secondary Features**
- [ ] Messaging tests
- [ ] Document management tests
- [ ] Reporting tests
- [ ] Calendar integration tests

### 10.2 Metrics Tracking

| Metric | Week 1 | Week 4 | Week 8 | Target |
|--------|--------|--------|--------|--------|
| Total Tests | 5 | 50 | 150 | 200+ |
| Line Coverage | 5% | 40% | 65% | 70% |
| Critical Paths | 0% | 80% | 100% | 100% |
| CI Build Time | - | <5min | <5min | <5min |

---

## Appendix A: Test Data Factories Required

| Factory | Status | Priority |
|---------|--------|----------|
| `UserFactory` | ✅ Exists | - |
| `ProjectFactory` | ✅ Exists | - |
| `TaskFactory` | ✅ Exists | - |
| `ServiceRequestFactory` | ⬜ Create | High |
| `PaymentFactory` | ⬜ Create | High |
| `PayoutFactory` | ⬜ Create | High |
| `TimeEntryFactory` | ⬜ Create | High |
| `WalletTransactionFactory` | ⬜ Create | High |
| `CouponFactory` | ⬜ Create | Medium |
| `ReferralFactory` | ⬜ Create | Medium |
| `ProjectAssignmentFactory` | ⬜ Create | Medium |
| `LoyaltyTierFactory` | ⬜ Create | Medium |
| `LoyaltyPointFactory` | ⬜ Create | Medium |

---

## Appendix B: Useful Assertions

```php
// HTTP assertions
$response->assertStatus(200);
$response->assertOk();
$response->assertRedirect('/dashboard');
$response->assertViewIs('admin.payouts.index');
$response->assertViewHas('payouts');
$response->assertSessionHas('success');
$response->assertSessionHasErrors('error');
$response->assertJson(['status' => 'success']);

// Database assertions
$this->assertDatabaseHas('payouts', ['status' => 'completed']);
$this->assertDatabaseMissing('time_entries', ['id' => $deletedId]);
$this->assertDatabaseCount('wallet_transactions', 5);

// Model assertions
$this->assertTrue($payout->isCompleted());
$this->assertEquals(1000, $user->work_earnings_balance);
$this->assertNull($timeEntry->approved_at);

// Mail assertions
Mail::fake();
Mail::assertSent(PayoutPaidMail::class);
Mail::assertSent(PayoutPaidMail::class, fn($mail) => $mail->hasTo('user@example.com'));

// Notification assertions
Notification::fake();
Notification::assertSentTo($user, PayoutPaidNotification::class);

// Collection assertions
$this->assertCount(5, $payouts);
$this->assertEmpty($errors);
```

---

*This document should be updated as tests are implemented and the testing strategy evolves.*
