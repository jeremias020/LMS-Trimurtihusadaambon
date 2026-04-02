<?php
echo "=== CEK STRUKTUR DATABASE ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Checking available tables...\n";
    $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- $tableName\n";
    }
    
    echo "\n=== CHECKING FOR KRITERIA TABLE ===\n";
    
    $possibleNames = ['kriteria_penilaian', 'kriteria_penilaians', 'assessment_criteria', 'criteria', 'kriteria'];
    $foundTable = null;
    
    foreach ($possibleNames as $name) {
        if (\Illuminate\Support\Facades\Schema::hasTable($name)) {
            echo "✅ Found table: $name\n";
            $foundTable = $name;
            
            echo "Columns:\n";
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing($name);
            foreach ($columns as $col) {
                echo "  - $col\n";
            }
            break;
        }
    }
    
    if (!$foundTable) {
        echo "❌ No kriteria table found. Creating kriteria_penilaian table...\n";
        
        // Create the table
        \Illuminate\Support\Facades\Schema::create('kriteria_penilaian', function ($table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori')->default('praktik');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('bobot')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('parent_id')->references('id')->on('kriteria_penilaian')->onDelete('cascade');
            $table->index(['kategori', 'is_active']);
        });
        
        echo "✅ Table kriteria_penilaian created successfully\n";
        
        echo "Columns:\n";
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('kriteria_penilaian');
        foreach ($columns as $col) {
            echo "  - $col\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";
?>
