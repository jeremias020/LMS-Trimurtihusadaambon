<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING PRACTICALS TABLE STRUCTURE ===\n\n";

try {
    echo "Table columns:\n";
    $columns = \Schema::getColumnListing('practicals');
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    echo "\nPractical model fillable fields:\n";
    $practical = new \App\Models\Practical();
    $fillable = $practical->getFillable();
    foreach ($fillable as $field) {
        echo "  - {$field}\n";
    }
    
    echo "\nExisting practical data:\n";
    $practicals = \App\Models\Practical::all();
    foreach ($practicals as $practical) {
        echo "  - ID: {$practical->id}\n";
        echo "    Created: {$practical->created_at}\n";
        echo "    Updated: {$practical->updated_at}\n";
        echo "    ---\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
