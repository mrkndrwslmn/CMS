<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Adiutor\AdiutorController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AiSearchController;
use App\Http\Controllers\PublicServiceRequestController;

// API routes
Route::prefix('api')->group(function () {
    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/aiSearch', [AiSearchController::class, 'search']);
});

// Public routes
Route::get('/', function () {
    return view('welcome');
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
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

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
    Route::post('/requests/{request}/confirm-payment', [\App\Http\Controllers\Admin\RequestManagementController::class, 'confirmPayment'])->name('requests.confirm-payment');
    Route::patch('/requests/{request}/priority', [\App\Http\Controllers\Admin\RequestManagementController::class, 'updatePriority'])->name('requests.update-priority');
    Route::post('/requests/{request}/notes', [\App\Http\Controllers\Admin\RequestManagementController::class, 'addNote'])->name('requests.add-note');
    Route::post('/requests/bulk-action', [\App\Http\Controllers\Admin\RequestManagementController::class, 'bulkAction'])->name('requests.bulk-action');
    Route::get('/requests/{request}/files/{file}/download', [\App\Http\Controllers\Admin\RequestManagementController::class, 'downloadFile'])->name('requests.download-file');
    Route::get('/requests/export', [\App\Http\Controllers\Admin\RequestManagementController::class, 'export'])->name('requests.export');
    
    // Document Management
    Route::resource('documents', \App\Http\Controllers\Admin\DocumentManagementController::class);
    Route::get('/documents/{document}/download', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'preview'])->name('documents.preview');
    Route::post('/documents/bulk-action', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'bulkAction'])->name('documents.bulk-action');
    Route::get('/documents/search', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'search'])->name('documents.search');
    
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
    Route::post('/projects/bulk-action', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'bulkAction'])->name('projects.bulk-action');
    
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
    });
    
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
});

// Client routes
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [ClientController::class, 'tasks'])->name('tasks');
    Route::get('/requests', [ClientController::class, 'requests'])->name('requests');
    Route::get('/feedback', [ClientController::class, 'feedback'])->name('feedback');
    
    // Service Request routes (authenticated only)
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'show'])->name('show');
        Route::get('/{id}/payment', [\App\Http\Controllers\Client\ServiceRequestController::class, 'showPayment'])->name('show-payment');
        Route::get('/{id}/edit', [\App\Http\Controllers\Client\ServiceRequestController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'destroy'])->name('destroy');
        Route::get('/{requestId}/attachments/{attachmentId}/download', [\App\Http\Controllers\Client\ServiceRequestController::class, 'downloadAttachment'])->name('attachment.download');
    });
    
    // Project routes
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/{id}', [ClientController::class, 'showProject'])->name('show');
    });
    
    // Feedback routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/{projectId}/create', [\App\Http\Controllers\Client\FeedbackController::class, 'create'])->name('create');
        Route::post('/{projectId}', [\App\Http\Controllers\Client\FeedbackController::class, 'store'])->name('store');
    });
});

// Adiutor routes
Route::middleware(['auth', 'role:adiutor'])->prefix('adiutor')->name('adiutor.')->group(function () {
    Route::get('/dashboard', [AdiutorController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [AdiutorController::class, 'tasks'])->name('tasks');
    Route::get('/clients', [AdiutorController::class, 'clients'])->name('clients');
    Route::get('/documents', [AdiutorController::class, 'documents'])->name('documents');
    Route::get('/feedback', [AdiutorController::class, 'feedback'])->name('feedback');
    
    // Task/Project management routes
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/{assignment}', [\App\Http\Controllers\Adiutor\TaskController::class, 'show'])->name('show');
        Route::post('/{assignment}/accept', [\App\Http\Controllers\Adiutor\TaskController::class, 'accept'])->name('accept');
        Route::post('/{assignment}/decline', [\App\Http\Controllers\Adiutor\TaskController::class, 'decline'])->name('decline');
        Route::post('/{assignment}/update-progress', [\App\Http\Controllers\Adiutor\TaskController::class, 'updateProgress'])->name('update-progress');
    });
    
    // Profile management routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [\App\Http\Controllers\Adiutor\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\Adiutor\ProfileController::class, 'update'])->name('update');
        Route::put('/skills', [\App\Http\Controllers\Adiutor\ProfileController::class, 'updateSkills'])->name('skills.update');
    });
});