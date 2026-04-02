<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG CRITERIA DATA STRUCTURE ===" . PHP_EOL;

try {
    // Get criteria data
    $criteria = DB::table('criteria')->get();
    
    echo "Total criteria: " . $criteria->count() . PHP_EOL;
    
    if ($criteria->count() > 0) {
        echo PHP_EOL . "Sample criteria structure:" . PHP_EOL;
        $sample = $criteria->first();
        echo "First criteria:" . PHP_EOL;
        echo "- ID: " . ($sample->id ?? 'NULL') . PHP_EOL;
        echo "- nama_kriteria: " . ($sample->nama_kriteria ?? 'NULL') . PHP_EOL;
        echo "- name: " . ($sample->name ?? 'NULL') . PHP_EOL;
        echo "- deskripsi: " . ($sample->deskripsi ?? 'NULL') . PHP_EOL;
        echo "- description: " . ($sample->description ?? 'NULL') . PHP_EOL;
        echo "- bobot: " . ($sample->bobot ?? 'NULL') . PHP_EOL;
        echo "- weight: " . ($sample->weight ?? 'NULL') . PHP_EOL;
        echo "- category: " . ($sample->category ?? 'NULL') . PHP_EOL;
        echo "- kategori: " . ($sample->kategori ?? 'NULL') . PHP_EOL;
        
        echo PHP_EOL . "All fields in criteria table:" . PHP_EOL;
        $fields = array_keys((array) $sample);
        foreach ($fields as $field) {
            echo "- $field: " . $sample->$field . PHP_EOL;
        }
        
        echo PHP_EOL . "Testing data transformation:" . PHP_EOL;
        $transformed = $criteria->map(function($item, $key) {
            return [
                'id' => $item->id ?? $key,
                'nama_kriteria' => $item->nama_kriteria ?? $item->name ?? 'Kriteria ' . ($key + 1),
                'deskripsi' => $item->deskripsi ?? $item->description ?? 'Deskripsi kriteria',
                'bobot' => $item->bobot ?? $item->weight ?? 0.1,
                'category' => $item->category ?? $item->kategori ?? 'umum'
            ];
        })->groupBy('category');
        
        echo "Grouped by category:" . PHP_EOL;
        foreach ($transformed as $category => $items) {
            echo "- $category: " . count($items) . " items" . PHP_EOL;
            if (count($items) > 0) {
                echo "  Sample: " . $items[0]['nama_kriteria'] . PHP_EOL;
            }
        }
        
        echo PHP_EOL . "JSON output test:" . PHP_EOL;
        $jsonOutput = json_encode($transformed);
        echo "JSON length: " . strlen($jsonOutput) . " characters" . PHP_EOL;
        echo "JSON valid: " . (json_decode($jsonOutput) !== null ? 'YES' : 'NO') . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
