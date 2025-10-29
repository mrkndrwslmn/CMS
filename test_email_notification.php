<?php
// Test email notification
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Get an admin user
    $admin = \App\Models\User::where('role', 'admin')->first();
    
    if (!$admin) {
        echo "❌ No admin user found\n";
        exit(1);
    }
    
    echo "🔍 Testing email notification...\n";
    echo "Admin: {$admin->fullName} ({$admin->email})\n";
    
    // Get a service request for testing
    $serviceRequest = \App\Models\ServiceRequest::first();
    
    if (!$serviceRequest) {
        echo "❌ No service request found for testing\n";
        exit(1);
    }
    
    echo "Service Request: {$serviceRequest->project_name}\n";
    
    // Test the notification
    try {
        $admin->notify(new \App\Notifications\NewServiceRequestNotification($serviceRequest, false));
        echo "✅ Notification sent successfully!\n";
    } catch (Exception $e) {
        echo "❌ Notification failed: " . $e->getMessage() . "\n";
        echo "Stack trace: " . $e->getTraceAsString() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>