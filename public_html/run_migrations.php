<?php

use Illuminate\Support\Facades\Artisan;

// Load Composer’s autoload and Laravel bootstrap files
require __DIR__ . '/bootstrap/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Run migrations with error handling
try {
    Artisan::call('migrate', ['--force' => true]); // Use '--force' for production
    echo "Migration completed successfully.";
} catch (\Exception $e) {
    echo "Migration failed with message: " . $e->getMessage();
    echo "<br><pre>" . $e->getTraceAsString() . "</pre>"; // Shows detailed error trace
}
