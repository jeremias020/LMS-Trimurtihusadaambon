<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PracticalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('guru.praktikum.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guru.praktikum.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement create practical logic
        return redirect()->route('guru.praktikum.index')->with('success', 'Praktikum berhasil dibuat (placeholder).');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // TODO: Implement show practical detail
        return view('guru.praktikum.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // TODO: Implement edit form
        return view('guru.praktikum.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement update logic
        return redirect()->route('guru.praktikum.index')->with('success', 'Praktikum berhasil diperbarui (placeholder).');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement delete logic
        return redirect()->route('guru.praktikum.index')->with('success', 'Praktikum berhasil dihapus (placeholder).');
    }
}
