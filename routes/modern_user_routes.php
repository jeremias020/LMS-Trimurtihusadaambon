<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ModernUserController;

// Modern User Management Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function() {
    
    // User Management - Separated Tables
    Route::get('/users/separated', [ModernUserController::class, 'index'])->name('users.index');
    
    // Create User - Different Forms for Each Role
    Route::get('/users/create/admin', [ModernUserController::class, 'createAdmin'])->name('users.create.admin');
    Route::get('/users/create/guru', [ModernUserController::class, 'createGuru'])->name('users.create.guru');
    Route::get('/users/create/siswa', [ModernUserController::class, 'createSiswa'])->name('users.create.siswa');
    
    // Store User - Different Methods for Each Role
    Route::post('/users/store/admin', [ModernUserController::class, 'storeAdmin'])->name('users.store.admin');
    Route::post('/users/store/guru', [ModernUserController::class, 'storeGuru'])->name('users.store.guru');
    Route::post('/users/store/siswa', [ModernUserController::class, 'storeSiswa'])->name('users.store.siswa');
    
    // Edit User - Single Method with Role Detection
    Route::get('/users/{id}/edit', [ModernUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [ModernUserController::class, 'update'])->name('users.update');
    
    // Delete User
    Route::delete('/users/{id}', [ModernUserController::class, 'destroy'])->name('users.destroy');
});

/*
USAGE EXAMPLES:

1. Access Separated User Management:
   URL: /admin/users/separated
   Route Name: admin.users.index

2. Create Different User Types:
   - Admin: /admin/users/create/admin (admin.users.create.admin)
   - Guru: /admin/users/create/guru (admin.users.create.guru)
   - Siswa: /admin/users/create/siswa (admin.users.create.siswa)

3. Store Different User Types:
   - Admin: POST /admin/users/store/admin (admin.users.store.admin)
   - Guru: POST /admin/users/store/guru (admin.users.store.guru)
   - Siswa: POST /admin/users/store/siswa (admin.users.store.siswa)

4. Edit Any User:
   URL: /admin/users/{id}/edit
   Route Name: admin.users.edit

5. Update Any User:
   PUT /admin/users/{id}
   Route Name: admin.users.update

6. Delete Any User:
   DELETE /admin/users/{id}
   Route Name: admin.users.destroy

BUTTON EXAMPLES IN VIEWS:

<!-- Create Buttons -->
<a href="{{ route('admin.users.create.admin') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>Tambah Admin
</a>
<a href="{{ route('admin.users.create.guru') }}" class="btn btn-success">
    <i class="fas fa-plus me-2"></i>Tambah Guru
</a>
<a href="{{ route('admin.users.create.siswa') }}" class="btn btn-warning">
    <i class="fas fa-plus me-2"></i>Tambah Siswa
</a>

<!-- Edit Button -->
<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
    <i class="fas fa-edit"></i>
</a>

<!-- Delete Button -->
<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus pengguna ini?')">
        <i class="fas fa-trash"></i>
    </button>
</form>

FORM ACTIONS:

<!-- Admin Form -->
<form action="{{ route('admin.users.store.admin') }}" method="POST">
    @csrf
    <!-- Admin form fields -->
</form>

<!-- Guru Form -->
<form action="{{ route('admin.users.store.guru') }}" method="POST">
    @csrf
    <!-- Guru form fields -->
</form>

<!-- Siswa Form -->
<form action="{{ route('admin.users.store.siswa') }}" method="POST">
    @csrf
    <!-- Siswa form fields -->
</form>
*/
