<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking adiutor.referrals routes:\n";
echo "================================\n";

foreach (app('router')->getRoutes() as $route) {
    $name = $route->getName();
    if ($name && str_starts_with($name, 'adiutor.referrals')) {
        echo $name . ' => ' . $route->uri() . "\n";
    }
}
