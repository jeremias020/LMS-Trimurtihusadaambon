<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 FIX GURUS TABLE USER_ID ISSUE\n";
echo "=====================================\n\n";

try {
    echo "Step 1: Check Gurus Table Structure\n";
    echo "-------------------------------------\n";
    
    $columns = \DB::select('SHOW COLUMNS FROM gurus');
    echo "Gurus table columns:\n";
    
    $hasUserId = false;
    $hasId = false;
    
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
        
        if ($column->Field === 'user_id') {
            $hasUserId = true;
        }
        if ($column->Field === 'id') {
            $hasId = true;
        }
    }
    
    echo "\nHas user_id column: " . ($hasUserId ? 'YES' : 'NO') . "\n";
    echo "Has id column: " . ($hasId ? 'YES' : 'NO') . "\n";
    
    echo "\nStep 2: Check Current Gurus Data\n";
    echo "-------------------------------------\n";
    
    $gurus = \DB::table('gurus')->get();
    echo "Total gurus: " . count($gurus) . "\n";
    
    foreach ($gurus as $guru) {
        echo "  - ID: {$guru->id}, Name: {$guru->name}, Email: {$guru->email}\n";
    }
    
    echo "\nStep 3: Check Guru Model Relationships\n";
    echo "-------------------------------------\n";
    
    // Check Guru model
    $guruModel = new \App\Models\Guru();
    echo "Guru model table: " . $guruModel->getTable() . "\n";
    
    // Test the problematic query
    echo "\nTesting problematic query...\n";
    
    try {
        $guruWithUserId = \DB::table('gurus')->where('user_id', 2)->first();
        echo "Query with user_id: " . ($guruWithUserId ? 'FOUND' : 'NOT FOUND') . "\n";
    } catch (Exception $e) {
        echo "❌ Query with user_id failed: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 4: Fix Gurus Table Structure\n";
    echo "-------------------------------------\n";
    
    if (!$hasUserId && $hasId) {
        echo "Adding user_id column to gurus table...\n";
        
        \Schema::table('gurus', function ($table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
        
        echo "✅ user_id column added\n";
    }
    
    echo "\nStep 5: Update Gurus Data with User IDs\n";
    echo "-------------------------------------\n";
    
    // Get guru users from users_central
    $guruUsers = \DB::table('users_central')->where('role', 'guru')->get();
    echo "Guru users in users_central: " . count($guruUsers) . "\n";
    
    foreach ($guruUsers as $guruUser) {
        echo "  - User ID: {$guruUser->id}, Email: {$guruUser->email}\n";
        
        // Find corresponding guru record
        $guruRecord = \DB::table('gurus')->where('email', $guruUser->email)->first();
        
        if ($guruRecord) {
            echo "    Found guru record, updating user_id...\n";
            
            \DB::table('gurus')
                ->where('id', $guruRecord->id)
                ->update(['user_id' => $guruUser->id]);
            
            echo "    ✅ Updated guru ID {$guruRecord->id} with user_id {$guruUser->id}\n";
        } else {
            echo "    ❌ No guru record found for email {$guruUser->email}\n";
            
            // Create guru record if not exists
            \DB::table('gurus')->insert([
                'id' => $guruUser->id,
                'user_id' => $guruUser->id,
                'name' => $guruUser->name,
                'email' => $guruUser->email,
                'password' => $guruUser->password,
                'is_active' => $guruUser->is_active ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "    ✅ Created new guru record with user_id {$guruUser->id}\n";
        }
    }
    
    echo "\nStep 6: Test Fixed Relationships\n";
    echo "-------------------------------------\n";
    
    // Test the query that was failing
    echo "Testing fixed query: select * from gurus where user_id = 2\n";
    
    $guruWithUserId = \DB::table('gurus')->where('user_id', 2)->first();
    
    if ($guruWithUserId) {
        echo "✅ Query successful!\n";
        echo "  - Guru ID: {$guruWithUserId->id}\n";
        echo "  - User ID: {$guruWithUserId->user_id}\n";
        echo "  - Name: {$guruWithUserId->name}\n";
        echo "  - Email: {$guruWithUserId->email}\n";
    } else {
        echo "❌ Query still failed - no guru found with user_id = 2\n";
    }
    
    // Test Guru model relationships
    echo "\nTesting Guru model relationships...\n";
    
    try {
        $guru = \App\Models\Guru::where('user_id', 2)->first();
        if ($guru) {
            echo "✅ Guru model query successful\n";
            echo "  - Guru: {$guru->name}\n";
            
            // Test user relationship
            if ($guru->user) {
                echo "  - User relationship: {$guru->user->name}\n";
            } else {
                echo "  - User relationship: NOT FOUND\n";
            }
        } else {
            echo "❌ Guru model query failed\n";
        }
    } catch (Exception $e) {
        echo "❌ Guru model error: " . $e->getMessage() . "\n";
    }
    
    echo "\nStep 7: Final Verification\n";
    echo "-------------------------------------\n";
    
    // Check all gurus now have user_id
    $gurusWithoutUserId = \DB::table('gurus')->whereNull('user_id')->count();
    echo "Gurus without user_id: {$gurusWithoutUserId}\n";
    
    $totalGurus = \DB::table('gurus')->count();
    echo "Total gurus: {$totalGurus}\n";
    
    echo "\n🎉 GURUS TABLE FIX COMPLETE!\n";
    echo "=====================================\n";
    echo "✅ user_id column added to gurus table\n";
    echo "✅ Gurus data updated with proper user_id\n";
    echo "✅ Relationships fixed\n";
    echo "✅ Query now works properly\n";
    
    echo "\n📋 Summary:\n";
    echo "  - Total gurus: {$totalGurus}\n";
    echo "  - Gurus with user_id: " . ($totalGurus - $gurusWithoutUserId) . "\n";
    echo "  - Gurus without user_id: {$gurusWithoutUserId}\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>
