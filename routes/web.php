<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminTemplateController;
use App\Http\Controllers\Admin\AdminAuditController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Adiutor\AdiutorController;
use App\Http\Controllers\Adiutor\TimeTrackingController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AiSearchController;
use App\Http\Controllers\PublicServiceRequestController;

// Email Template Preview Routes (for development)
if (app()->environment(['local', 'development', 'staging'])) {
    Route::get('/preview-email/{template}', function ($template) {
        // Mock data for email templates
        $mockUser = (object) [
            'fullName' => 'John Doe',
            'firstName' => 'John',
            'email' => 'john.doe@example.com'
        ];
        
        $mockServiceRequest = (object) [
            'project_name' => 'Modern Website Redesign',
            'service_type' => 'Web Development',
            'approved_budget' => 5000,
            'payment_due_date' => now()->addDays(14),
            'payment_reference' => 'REF-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'payment_confirmed_at' => now(),
            'payment_instructions' => 'Please pay via bank transfer to the account details provided.',
            'client' => $mockUser
        ];
        
        $mockProject = (object) [
            'project_name' => 'E-commerce Platform Development',
            'service_type' => 'Full Stack Development',
            'completed_at' => now(),
            'created_at' => now()->subDays(30),
            'client' => $mockUser
        ];
        
        $mockRevision = (object) [
            'revision_type' => 'Design Update',
            'approved_at' => now(),
            'rejected_at' => now(),
            'rejection_reason' => 'The color scheme needs to be adjusted to better match the brand guidelines. Please use more blue tones and reduce the intensity of the orange accents.',
            'description' => 'Update the header design with new branding elements and improve mobile responsiveness.',
            'estimated_completion' => now()->addDays(7),
            'project' => $mockProject
        ];
        
        switch ($template) {
            case 'new-user-credentials':
                return view('emails.new-user-credentials', [
                    'fullName' => $mockUser->fullName,
                    'email' => $mockUser->email,
                    'password' => 'TempPass123!'
                ]);
                
            case 'payment-confirmed':
                return view('emails.payment-confirmed', [
                    'serviceRequest' => $mockServiceRequest
                ]);
                
            case 'request-approved':
                return view('emails.request-approved', [
                    'serviceRequest' => $mockServiceRequest
                ]);
                
            case 'project-completed':
                return view('emails.project-completed', [
                    'project' => $mockProject
                ]);
                
            case 'revision-approved':
                return view('emails.revision-approved', [
                    'revision' => $mockRevision
                ]);
                
            case 'revision-rejected':
                return view('emails.revision-rejected', [
                    'revision' => $mockRevision
                ]);
                
            default:
                abort(404, 'Email template not found');
        }
    })->name('email.preview');
}

// API routes
Route::prefix('api')->group(function () {
    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/aiSearch', [AiSearchController::class, 'search']);
    
    // Showcase routes
    Route::get('/showcases', [\App\Http\Controllers\Api\ShowcaseController::class, 'index']);
    Route::get('/showcases/{slug}', [\App\Http\Controllers\Api\ShowcaseController::class, 'show']);
    Route::get('/tech_stack', [\App\Http\Controllers\Api\ShowcaseController::class, 'techStack']);
    
    // Chatbot routes
    Route::post('/chatbot/chat', [\App\Http\Controllers\ChatbotController::class, 'chat']);
    Route::get('/chatbot/greeting', [\App\Http\Controllers\ChatbotController::class, 'greeting']);
    
    // Messaging API routes (requires authentication)
    Route::middleware('auth')->group(function () {
        Route::prefix('messages')->name('api.messages.')->group(function () {
            Route::get('/conversations', [\App\Http\Controllers\Api\MessageController::class, 'index'])->name('conversations');
            Route::get('/unread-count', [\App\Http\Controllers\Api\MessageController::class, 'unreadCount'])->name('unread-count');
            Route::post('/fcm-token', [\App\Http\Controllers\Api\MessageController::class, 'updateFcmToken'])->name('update-fcm-token');
            Route::get('/projects/{project}', [\App\Http\Controllers\Api\MessageController::class, 'show'])->name('show');
            Route::post('/projects/{project}', [\App\Http\Controllers\Api\MessageController::class, 'store'])->name('store');
            Route::post('/projects/{project}/mark-read', [\App\Http\Controllers\Api\MessageController::class, 'markAsRead'])->name('mark-read');
            Route::delete('/{message}', [\App\Http\Controllers\Api\MessageController::class, 'destroy'])->name('destroy');
        });
        
        // Meeting API routes
        Route::prefix('meetings')->name('api.meetings.')->group(function () {
            Route::get('/projects/{project}', [\App\Http\Controllers\Api\MeetingController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Api\MeetingController::class, 'store'])->name('store');
            Route::put('/{meeting}/approve', [\App\Http\Controllers\Api\MeetingController::class, 'approve'])->name('approve');
            Route::put('/{meeting}/reschedule', [\App\Http\Controllers\Api\MeetingController::class, 'reschedule'])->name('reschedule');
            Route::put('/{meeting}/reject', [\App\Http\Controllers\Api\MeetingController::class, 'reject'])->name('reject');
            Route::put('/{meeting}/approve-reschedule', [\App\Http\Controllers\Api\MeetingController::class, 'approveReschedule'])->name('approve-reschedule');
            Route::put('/{meeting}/reject-reschedule', [\App\Http\Controllers\Api\MeetingController::class, 'rejectReschedule'])->name('reject-reschedule');
            Route::delete('/{meeting}', [\App\Http\Controllers\Api\MeetingController::class, 'destroy'])->name('destroy');
        });
    });
});

// Public routes
Route::get('/', function () {
    // Get active announcements for public
    $announcements = DB::table('announcements')
        ->join('users', 'announcements.created_by', '=', 'users.id')
        ->where('announcements.status', 'active')
        ->whereIn('announcements.target_audience', ['public', 'all'])
        ->where(function ($query) {
            $query->whereNull('announcements.expires_at')
                  ->orWhere('announcements.expires_at', '>', now());
        })
        ->where(function ($query) {
            $query->whereNull('announcements.starts_at')
                  ->orWhere('announcements.starts_at', '<=', now());
        })
        ->select(
            'announcements.id',
            'announcements.title',
            'announcements.content',
            'announcements.priority',
            'announcements.status',
            'announcements.starts_at',
            'announcements.expires_at',
            'announcements.created_at',
            'users.fullName as creator_name'
        )
        ->orderByRaw("FIELD(announcements.priority, 'high', 'medium', 'low')")
        ->orderBy('announcements.created_at', 'desc')
        ->get();
    
    return view('welcome', compact('announcements'));
})->name('home');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/about-us', function () {
    return view('about');
})->name('about-us');

Route::get('/contact', function () {
    $user = Auth::user();
    $isLoggedIn = $user !== null;
    return view('get-started', compact('user', 'isLoggedIn'));
})->name('contact');

Route::get('/featured-projects', function () {
    return view('featured-projects');
})->name('featured-projects');

Route::get('/project-details', function () {
    return view('project-details');
})->name('project-details');

Route::get('/client-testimonials', function () {
    return view('client-testimonials');
})->name('client-testimonials');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms-and-conditions');

// Public service request form
Route::get('/get-started', [PublicServiceRequestController::class, 'create'])->name('get-started');
Route::post('/get-started', [PublicServiceRequestController::class, 'store'])->name('get-started.submit');

// Public service request routes (accessible without authentication)
Route::get('/client/requests/create', [\App\Http\Controllers\Client\ServiceRequestController::class, 'create'])->name('client.requests.create');
Route::post('/client/requests/store', [\App\Http\Controllers\Client\ServiceRequestController::class, 'store'])->name('client.requests.store');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Firebase Authentication Routes
    Route::prefix('auth/firebase')->name('firebase.')->group(function () {
        Route::post('/callback', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'handleCallback'])->name('callback');
        Route::get('/config', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'getConfig'])->name('config');
    });
});

// Firebase account linking (requires authentication)
Route::middleware('auth')->prefix('auth/firebase')->name('firebase.')->group(function () {
    Route::post('/link/initiate', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'initiateLink'])->name('link.initiate');
    Route::post('/unlink', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'unlinkAccount'])->name('unlink');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Notification routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/notifications/fetch', [\App\Http\Controllers\NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Admin authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });
    
    Route::post('/logout', [AdminController::class, 'logout'])->middleware('auth')->name('logout');
});

// Admin routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/refresh', [AdminController::class, 'refreshDashboard'])->name('dashboard.refresh');
    Route::get('/dashboard/download-report', [AdminController::class, 'downloadReport'])->name('dashboard.download');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::get('/api/announcements/active', [\App\Http\Controllers\Admin\AnnouncementController::class, 'getActive'])->name('announcements.active');
    
    // Budget Change Requests
    Route::prefix('budget-requests')->name('budget-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'destroy'])->name('destroy');
    });
    
    // Payment Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'export'])->name('export');
        Route::get('/{id}', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'updateStatus'])->name('update-status');
    });
    
    // Project Template Management
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', [AdminTemplateController::class, 'index'])->name('index');
        Route::get('/create', [AdminTemplateController::class, 'create'])->name('create');
        Route::post('/', [AdminTemplateController::class, 'store'])->name('store');
        Route::get('/{template}', [AdminTemplateController::class, 'show'])->name('show');
        Route::get('/{template}/edit', [AdminTemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [AdminTemplateController::class, 'update'])->name('update');
        Route::delete('/{template}', [AdminTemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{template}/toggle', [AdminTemplateController::class, 'toggle'])->name('toggle');
        Route::post('/{template}/duplicate', [AdminTemplateController::class, 'duplicate'])->name('duplicate');
    });
    
    // Audit Log Management
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [AdminAuditController::class, 'index'])->name('index');
        Route::get('/{auditLog}', [AdminAuditController::class, 'show'])->name('show');
        Route::get('/api/logs', [AdminAuditController::class, 'logs'])->name('logs');
        Route::get('/api/statistics', [AdminAuditController::class, 'statistics'])->name('statistics');
        Route::get('/export/csv', [AdminAuditController::class, 'export'])->name('export');
        Route::post('/cleanup', [AdminAuditController::class, 'cleanup'])->name('cleanup');
    });
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
    Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/bulk-action', [\App\Http\Controllers\Admin\UserManagementController::class, 'bulkAction'])->name('users.bulk-action');
    
    // Client Management
    Route::resource('clients', \App\Http\Controllers\Admin\ClientManagementController::class);
    Route::post('/clients/{client}/notes', [\App\Http\Controllers\Admin\ClientManagementController::class, 'addNote'])->name('clients.notes.store');
    Route::put('/clients/{client}/notes/{note}', [\App\Http\Controllers\Admin\ClientManagementController::class, 'updateNote'])->name('clients.notes.update');
    Route::delete('/clients/{client}/notes/{note}', [\App\Http\Controllers\Admin\ClientManagementController::class, 'deleteNote'])->name('clients.notes.destroy');
    
    // Task Management
    Route::resource('tasks', \App\Http\Controllers\Admin\TaskManagementController::class);
    Route::post('/tasks/{task}/assign', [\App\Http\Controllers\Admin\TaskManagementController::class, 'assign'])->name('tasks.assign');
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\Admin\TaskManagementController::class, 'updateStatus'])->name('tasks.update-status');
    Route::patch('/tasks/{task}/update-notes', [\App\Http\Controllers\Admin\TaskManagementController::class, 'updateNotes'])->name('tasks.update-notes');
    Route::patch('/tasks/{task}/update-budget', [\App\Http\Controllers\Admin\TaskManagementController::class, 'updateBudget'])->name('tasks.update-budget');
    Route::get('/service-requests/{serviceRequest}/budget-overview', [\App\Http\Controllers\Admin\TaskManagementController::class, 'budgetOverview'])->name('service-requests.budget-overview');
    Route::post('/tasks/bulk-action', [\App\Http\Controllers\Admin\TaskManagementController::class, 'bulkAction'])->name('tasks.bulk-action');
    
    // Request Management
    Route::resource('requests', \App\Http\Controllers\Admin\RequestManagementController::class, ['only' => ['index', 'show']]);
    Route::post('/requests/{request}/approve', [\App\Http\Controllers\Admin\RequestManagementController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{request}/reject', [\App\Http\Controllers\Admin\RequestManagementController::class, 'reject'])->name('requests.reject');
    Route::post('/requests/{request}/reopen', [\App\Http\Controllers\Admin\RequestManagementController::class, 'reopen'])->name('requests.reopen');
    Route::post('/requests/{request}/request-payment', [\App\Http\Controllers\Admin\RequestManagementController::class, 'requestPayment'])->name('requests.request-payment');
    // Note: confirmPayment is deprecated - Maya payment gateway handles confirmation automatically
    Route::post('/requests/{request}/confirm-payment', [\App\Http\Controllers\Admin\RequestManagementController::class, 'confirmPayment'])->name('requests.confirm-payment');
    Route::patch('/requests/{request}/priority', [\App\Http\Controllers\Admin\RequestManagementController::class, 'updatePriority'])->name('requests.update-priority');
    Route::post('/requests/{request}/notes', [\App\Http\Controllers\Admin\RequestManagementController::class, 'addNote'])->name('requests.add-note');
    Route::post('/requests/bulk-action', [\App\Http\Controllers\Admin\RequestManagementController::class, 'bulkAction'])->name('requests.bulk-action');
    Route::get('/requests/{request}/files/{file}/download', [\App\Http\Controllers\Admin\RequestManagementController::class, 'downloadFile'])->name('requests.download-file');
    Route::get('/requests/export', [\App\Http\Controllers\Admin\RequestManagementController::class, 'export'])->name('requests.export');
    
    // Coupon Management
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('store');
        Route::get('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'show'])->name('show');
        Route::get('/{coupon}/edit', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('edit');
        Route::put('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('destroy');
        Route::post('/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('toggle');
        Route::get('/{coupon}/usage', [\App\Http\Controllers\Admin\CouponController::class, 'usageHistory'])->name('usage');
        Route::post('/bulk-generate', [\App\Http\Controllers\Admin\CouponController::class, 'bulkGenerate'])->name('bulk');
        Route::post('/check-code', [\App\Http\Controllers\Admin\CouponController::class, 'checkCode'])->name('check-code');
    });
    
    // Loyalty Program Management
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LoyaltyController::class, 'index'])->name('index');
        Route::get('/leaderboard', [\App\Http\Controllers\Admin\LoyaltyController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/transactions', [\App\Http\Controllers\Admin\LoyaltyController::class, 'transactions'])->name('transactions');
        Route::get('/settings/tiers', [\App\Http\Controllers\Admin\LoyaltyController::class, 'tierSettings'])->name('settings');
        Route::put('/settings/tiers', [\App\Http\Controllers\Admin\LoyaltyController::class, 'updateTierSettings'])->name('settings.update');
        Route::get('/export/report', [\App\Http\Controllers\Admin\LoyaltyController::class, 'exportLoyaltyReport'])->name('export');
        Route::post('/expiry-warnings', [\App\Http\Controllers\Admin\LoyaltyController::class, 'sendExpiryWarnings'])->name('expiry-warnings');
        Route::get('/dashboard-widget', [\App\Http\Controllers\Admin\LoyaltyController::class, 'dashboardWidget'])->name('widget');
        Route::get('/{user}', [\App\Http\Controllers\Admin\LoyaltyController::class, 'show'])->name('show');
        Route::post('/{user}/adjust', [\App\Http\Controllers\Admin\LoyaltyController::class, 'adjustPoints'])->name('adjust');
    });
    
    // Referral Program Management
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReferralController::class, 'index'])->name('index');
        Route::get('/list', [\App\Http\Controllers\Admin\ReferralController::class, 'list'])->name('list');
        Route::get('/codes', [\App\Http\Controllers\Admin\ReferralController::class, 'codes'])->name('codes');
        Route::get('/analytics', [\App\Http\Controllers\Admin\ReferralController::class, 'analytics'])->name('analytics');
        Route::get('/export', [\App\Http\Controllers\Admin\ReferralController::class, 'export'])->name('export');
        Route::get('/{referral}', [\App\Http\Controllers\Admin\ReferralController::class, 'show'])->name('show');
        Route::post('/{referral}/process', [\App\Http\Controllers\Admin\ReferralController::class, 'processPending'])->name('process');
        Route::patch('/codes/{code}/toggle', [\App\Http\Controllers\Admin\ReferralController::class, 'toggleCodeStatus'])->name('codes.toggle');
    });
    
    // Document Management
    Route::resource('documents', \App\Http\Controllers\Admin\DocumentManagementController::class);
    Route::get('/documents/{document}/download', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'preview'])->name('documents.preview');
    Route::post('/documents/bulk-action', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'bulkAction'])->name('documents.bulk-action');
    Route::get('/documents/search', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'search'])->name('documents.search');
    Route::post('/projects/{project}/documents', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'uploadToProject'])->name('projects.documents.upload');
    
    // Revision Management
    Route::prefix('revisions')->name('revisions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RevisionController::class, 'index'])->name('index');
        Route::get('/{revision}', [\App\Http\Controllers\Admin\RevisionController::class, 'show'])->name('show');
        Route::post('/{revision}/approve', [\App\Http\Controllers\Admin\RevisionController::class, 'approve'])->name('approve');
        Route::post('/{revision}/reject', [\App\Http\Controllers\Admin\RevisionController::class, 'reject'])->name('reject');
        Route::post('/{revision}/reassign', [\App\Http\Controllers\Admin\RevisionController::class, 'reassign'])->name('reassign');
    });
    
    // Feedback Management
    Route::resource('feedback', \App\Http\Controllers\Admin\FeedbackManagementController::class, ['only' => ['index', 'show']]);
    Route::post('/feedback/{feedback}/respond', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'respond'])->name('feedback.respond');
    Route::patch('/feedback/{feedback}/status', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'updateStatus'])->name('feedback.update-status');
    Route::post('/feedback/{feedback}/assign', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'assignTo'])->name('feedback.assign');
    Route::post('/feedback/{feedback}/notes', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'addNote'])->name('feedback.add-note');
    Route::post('/feedback/bulk-action', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'bulkAction'])->name('feedback.bulk-action');
    Route::get('/feedback/analytics', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'analytics'])->name('feedback.analytics');
    Route::get('/feedback/export', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'export'])->name('feedback.export');
    Route::get('/feedback/summary', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'summary'])->name('feedback.summary');
    
    // Workflow Management (Service Request → Project → Task)
    Route::prefix('workflow')->name('workflow.')->group(function () {
        Route::post('/requests/{request}/approve', [\App\Http\Controllers\Admin\WorkflowController::class, 'approveRequest'])->name('requests.approve');
        Route::post('/requests/{request}/reject', [\App\Http\Controllers\Admin\WorkflowController::class, 'rejectRequest'])->name('requests.reject');
        Route::post('/requests/{request}/confirm-payment', [\App\Http\Controllers\Admin\WorkflowController::class, 'confirmPayment'])->name('requests.confirm-payment');
        Route::post('/projects/{project}/tasks', [\App\Http\Controllers\Admin\WorkflowController::class, 'createTask'])->name('projects.tasks.create');
        Route::get('/projects/{project}/budget-overview', [\App\Http\Controllers\Admin\WorkflowController::class, 'getProjectBudgetOverview'])->name('projects.budget-overview');
    });

    // Project Management (NEW)
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectManagementController::class);
    Route::post('/projects/{project}/notes', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'addNote'])->name('projects.notes.store');
    Route::patch('/projects/{project}/status', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'updateStatus'])->name('projects.update-status');
    Route::patch('/projects/{project}/complete', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'complete'])->name('projects.complete');
    Route::post('/projects/bulk-action', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'bulkAction'])->name('projects.bulk-action');
    
    // Team Management routes
    Route::post('/projects/{project}/assign-adiutor', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'assignAdiutor'])->name('projects.assign-adiutor');
    Route::delete('/projects/{project}/adiutors/{adiutor}', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'removeAdiutor'])->name('projects.remove-adiutor');
    Route::get('/projects/{project}/team-members', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'getTeamMembers'])->name('projects.team-members');
    Route::get('/projects/{project}/phases', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'getProjectPhases'])->name('projects.phases');
    
    // Reporting and Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReportingController::class, 'index'])->name('index');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\ReportingController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\ReportingController::class, 'users'])->name('users');
        Route::get('/tasks', [\App\Http\Controllers\Admin\ReportingController::class, 'tasks'])->name('tasks');
        Route::get('/requests', [\App\Http\Controllers\Admin\ReportingController::class, 'requests'])->name('requests');
        Route::get('/projects', [\App\Http\Controllers\Admin\ReportingController::class, 'projects'])->name('projects');
        Route::get('/documents', [\App\Http\Controllers\Admin\ReportingController::class, 'documents'])->name('documents');
        Route::get('/export', [\App\Http\Controllers\Admin\ReportingController::class, 'export'])->name('export');
        Route::get('/custom', [\App\Http\Controllers\Admin\ReportingController::class, 'customReport'])->name('custom');
        
        // Custom Reports API endpoints
        Route::post('/custom/generate', [\App\Http\Controllers\Admin\ReportingController::class, 'generateCustomReport'])->name('custom.generate');
        Route::post('/custom/preview', [\App\Http\Controllers\Admin\ReportingController::class, 'previewCustomReport'])->name('custom.preview');
        Route::post('/custom/templates', [\App\Http\Controllers\Admin\ReportingController::class, 'saveCustomTemplate'])->name('custom.template.save');
        Route::get('/custom/templates', [\App\Http\Controllers\Admin\ReportingController::class, 'getCustomTemplates'])->name('custom.templates');
        Route::get('/custom/filters/{type}', [\App\Http\Controllers\Admin\ReportingController::class, 'getFilterOptions'])->name('custom.filters');
    });
    
    // Messaging routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [AdminController::class, 'messages'])->name('index');
        Route::get('/projects/{project}', [AdminController::class, 'showMessages'])->name('show');
    });
    
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
});

// Client routes
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects', [ClientController::class, 'index'])->name('projects.index');
    Route::get('/tasks', [ClientController::class, 'tasks'])->name('tasks');
    Route::get('/requests', [ClientController::class, 'requests'])->name('requests');
    Route::get('/feedback', [ClientController::class, 'feedback'])->name('feedback');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Service Request routes (authenticated only)
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'show'])->name('show');
        Route::get('/{id}/payment', [\App\Http\Controllers\Client\ServiceRequestController::class, 'showPayment'])->name('show-payment');
        Route::get('/{id}/edit', [\App\Http\Controllers\Client\ServiceRequestController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'destroy'])->name('destroy');
        Route::get('/{requestId}/attachments/{attachmentId}/download', [\App\Http\Controllers\Client\ServiceRequestController::class, 'downloadAttachment'])->name('attachment.download');
    });
    
    // Maya Payment routes
    Route::prefix('maya')->name('maya.')->group(function () {
        Route::get('/checkout/{serviceRequestId}', [\App\Http\Controllers\Client\MayaPaymentController::class, 'checkout'])->name('checkout');
        Route::get('/success', [\App\Http\Controllers\Client\MayaPaymentController::class, 'success'])->name('success');
        Route::get('/failure', [\App\Http\Controllers\Client\MayaPaymentController::class, 'failure'])->name('failure');
        Route::get('/cancel', [\App\Http\Controllers\Client\MayaPaymentController::class, 'cancel'])->name('cancel');
    });
    
    // Payment History routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\PaymentHistoryController::class, 'index'])->name('history');
        Route::get('/{id}', [\App\Http\Controllers\Client\PaymentHistoryController::class, 'show'])->name('show');
        Route::get('/{id}/receipt', [\App\Http\Controllers\Client\PaymentHistoryController::class, 'receipt'])->name('receipt');
    });
    
    // Project routes
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/{id}', [ClientController::class, 'showProject'])->name('show');
        Route::get('/{projectId}/documents/{documentId}/download', [ClientController::class, 'downloadDocument'])->name('documents.download');
    });
    
    // Feedback routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/{projectId}/create', [\App\Http\Controllers\Client\FeedbackController::class, 'create'])->name('create');
        Route::post('/{projectId}', [\App\Http\Controllers\Client\FeedbackController::class, 'store'])->name('store');
    });
    
    // Coupon routes
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\CouponController::class, 'index'])->name('index');
        Route::get('/{coupon}', [\App\Http\Controllers\Client\CouponController::class, 'show'])->name('show');
        Route::post('/validate', [\App\Http\Controllers\Client\CouponController::class, 'validateCode'])->name('validate');
        Route::post('/requests/{serviceRequest}/apply', [\App\Http\Controllers\Client\CouponController::class, 'applyCoupon'])->name('apply');
        Route::delete('/requests/{serviceRequest}/remove', [\App\Http\Controllers\Client\CouponController::class, 'removeCoupon'])->name('remove');
        Route::post('/{coupon}/copy', [\App\Http\Controllers\Client\CouponController::class, 'copyCode'])->name('copy');
    });
    
    // Loyalty Program routes
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\LoyaltyController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [\App\Http\Controllers\Client\LoyaltyController::class, 'transactions'])->name('transactions');
        Route::post('/requests/{serviceRequest}/redeem', [\App\Http\Controllers\Client\LoyaltyController::class, 'redeemPoints'])->name('redeem');
        Route::delete('/requests/{serviceRequest}/remove-redemption', [\App\Http\Controllers\Client\LoyaltyController::class, 'removeRedemption'])->name('remove-redemption');
        Route::get('/widget-data', [\App\Http\Controllers\Client\LoyaltyController::class, 'widgetData'])->name('widget-data');
        Route::post('/calculate-earning', [\App\Http\Controllers\Client\LoyaltyController::class, 'calculateEarning'])->name('calculate-earning');
        Route::post('/calculate-discount', [\App\Http\Controllers\Client\LoyaltyController::class, 'calculateDiscount'])->name('calculate-discount');
    });
    
    // Referral Program routes
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\ReferralController::class, 'dashboard'])->name('dashboard');
        Route::get('/share', [\App\Http\Controllers\Client\ReferralController::class, 'share'])->name('share');
        Route::get('/history', [\App\Http\Controllers\Client\ReferralController::class, 'history'])->name('history');
        Route::get('/code', [\App\Http\Controllers\Client\ReferralController::class, 'getCode'])->name('code');
        Route::get('/stats', [\App\Http\Controllers\Client\ReferralController::class, 'getStats'])->name('stats');
        Route::post('/validate', [\App\Http\Controllers\Client\ReferralController::class, 'validateCode'])->name('validate');
        Route::post('/invite', [\App\Http\Controllers\Client\ReferralController::class, 'sendInvitation'])->name('invite');
        Route::post('/generate-link', [\App\Http\Controllers\Client\ReferralController::class, 'generateLink'])->name('generate-link');
    });
    
    // Revision Request routes
    Route::prefix('revisions')->name('revisions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\RevisionRequestController::class, 'index'])->name('index');
        Route::get('/{revision}', [\App\Http\Controllers\Client\RevisionRequestController::class, 'show'])->name('show');
        
        // Project-level revisions
        Route::get('/projects/{project}/create', [\App\Http\Controllers\Client\RevisionRequestController::class, 'createForProject'])->name('project.create');
        Route::post('/projects/{project}', [\App\Http\Controllers\Client\RevisionRequestController::class, 'storeForProject'])->name('project.store');
        
        // Document-level revisions
        Route::get('/documents/{document}/create', [\App\Http\Controllers\Client\RevisionRequestController::class, 'create'])->name('create');
        Route::post('/documents/{document}', [\App\Http\Controllers\Client\RevisionRequestController::class, 'store'])->name('store');
        
        Route::post('/{revision}/cancel', [\App\Http\Controllers\Client\RevisionRequestController::class, 'cancel'])->name('cancel');
    });
    
    // Messaging routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [ClientController::class, 'messages'])->name('index');
        Route::get('/projects/{project}', [ClientController::class, 'showMessages'])->name('show');
    });
});

// Adiutor routes
Route::middleware(['auth', 'role:adiutor'])->prefix('adiutor')->name('adiutor.')->group(function () {
    Route::get('/dashboard', [AdiutorController::class, 'dashboard'])->name('dashboard');
    Route::get('/clients', [AdiutorController::class, 'clients'])->name('clients');
    Route::get('/documents', [AdiutorController::class, 'documents'])->name('documents');
    Route::get('/feedback', [AdiutorController::class, 'feedback'])->name('feedback');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Projects Routes (Separate from Tasks)
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\ProjectController::class, 'index'])->name('index');
        Route::get('/{project}', [\App\Http\Controllers\Adiutor\ProjectController::class, 'show'])->name('show');
        Route::post('/{assignment}/accept', [\App\Http\Controllers\Adiutor\ProjectController::class, 'accept'])->name('accept');
        Route::post('/{assignment}/decline', [\App\Http\Controllers\Adiutor\ProjectController::class, 'decline'])->name('decline');
        Route::post('/{assignment}/update-progress', [\App\Http\Controllers\Adiutor\ProjectController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{project}/create-task', [\App\Http\Controllers\Adiutor\ProjectController::class, 'createTask'])->name('create-task');
    });
    
    // Tasks Routes (Individual tasks within projects)
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\TaskController::class, 'index'])->name('index');
        Route::get('/{task}', [\App\Http\Controllers\Adiutor\TaskController::class, 'show'])->name('show');
        Route::post('/{task}/update-status', [\App\Http\Controllers\Adiutor\TaskController::class, 'updateStatus'])->name('update-status');
        Route::post('/{task}/complete', [\App\Http\Controllers\Adiutor\TaskController::class, 'markCompleted'])->name('complete');
        Route::post('/{task}/add-note', [\App\Http\Controllers\Adiutor\TaskController::class, 'addNote'])->name('add-note');
        Route::post('/{task}/upload-file', [\App\Http\Controllers\Adiutor\TaskController::class, 'uploadFile'])->name('upload-file');
        Route::get('/download-file/{fileId}', [\App\Http\Controllers\Adiutor\TaskController::class, 'downloadFile'])->name('download-file');
        Route::delete('/delete-file/{fileId}', [\App\Http\Controllers\Adiutor\TaskController::class, 'deleteFile'])->name('delete-file');
        Route::post('/{task}/request-budget-change', [\App\Http\Controllers\Adiutor\TaskController::class, 'requestBudgetChange'])->name('request-budget-change');
        Route::post('/{task}/update-progress', [\App\Http\Controllers\Adiutor\TaskController::class, 'updateTaskProgress'])->name('update-progress');
    });
    
    // Time Tracking Routes
    Route::prefix('time-tracking')->name('time-tracking.')->group(function () {
        Route::get('/', [TimeTrackingController::class, 'index'])->name('index');
        Route::get('/status', [TimeTrackingController::class, 'status'])->name('status');
        Route::get('/entries', [TimeTrackingController::class, 'entries'])->name('entries');
        Route::post('/start', [TimeTrackingController::class, 'start'])->name('start');
        Route::post('/stop', [TimeTrackingController::class, 'stop'])->name('stop');
        Route::put('/entries/{timeEntry}', [TimeTrackingController::class, 'update'])->name('entries.update');
        Route::delete('/entries/{timeEntry}', [TimeTrackingController::class, 'delete'])->name('entries.delete');
    });
    
    // Profile management routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [\App\Http\Controllers\Adiutor\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\Adiutor\ProfileController::class, 'update'])->name('update');
        Route::put('/skills', [\App\Http\Controllers\Adiutor\ProfileController::class, 'updateSkills'])->name('skills.update');
    });
    
    // Revision Management routes
    Route::prefix('revisions')->name('revisions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\RevisionController::class, 'index'])->name('index');
        Route::get('/{revision}', [\App\Http\Controllers\Adiutor\RevisionController::class, 'show'])->name('show');
        Route::get('/{revision}/upload', [\App\Http\Controllers\Adiutor\RevisionController::class, 'uploadForm'])->name('upload');
        Route::post('/{revision}/complete', [\App\Http\Controllers\Adiutor\RevisionController::class, 'complete'])->name('complete');
    });
});