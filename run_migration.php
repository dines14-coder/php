<?php
// Simple migration runner script
// Run this file via: php run_migration.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Run the specific migration
$exitCode = $kernel->call('migrate', [
    '--path' => 'database/migrations/2024_01_15_000000_add_is_first_login_to_emp_profile_tbls.php'
]);

echo "Migration completed with exit code: " . $exitCode . "\n";