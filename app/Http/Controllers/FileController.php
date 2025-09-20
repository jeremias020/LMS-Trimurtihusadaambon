<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Download file from storage.
     *
     * @param string $folder
     * @param string $filename
     * @return mixed
     */
    public function downloadFile(string $folder, string $filename)
    {
        $path = "{$folder}/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Cara 1: Menggunakan response()->download()
        $fullPath = Storage::disk('public')->path($path);
        return response()->download($fullPath, $filename);
        
        // // Cara 2: Menggunakan header manual (jika cara 1 masih error)
        // $fileContent = Storage::disk('public')->get($path);
        // $mimeType = Storage::disk('public')->mimeType($path);
        
        // return response($fileContent, 200)
        //     ->header('Content-Type', $mimeType)
        //     ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * View file from storage.
     *
     * @param string $folder
     * @param string $filename
     * @return mixed
     */
    public function viewFile(string $folder, string $filename)
    {
        $path = "{$folder}/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Cara 1: Menggunakan response()->file()
        $fullPath = Storage::disk('public')->path($path);
        return response()->file($fullPath);
        
        // // Cara 2: Menggunakan header manual (jika cara 1 masih error)
        // $fileContent = Storage::disk('public')->get($path);
        // $mimeType = Storage::disk('public')->mimeType($path);
        
        // return response($fileContent, 200)
        //     ->header('Content-Type', $mimeType)
        //     ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    /**
     * Get file information
     *
     * @param string $folder
     * @param string $filename
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFileInfo(string $folder, string $filename)
    {
        $path = "{$folder}/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        $size = Storage::disk('public')->size($path);
        $lastModified = Storage::disk('public')->lastModified($path);

        return response()->json([
            'filename' => $filename,
            'size' => $this->formatFileSize($size),
            'size_bytes' => $size,
            'last_modified' => date('Y-m-d H:i:s', $lastModified),
            'path' => $path
        ]);
    }

    /**
     * Format file size to human readable format
     *
     * @param int $bytes
     * @return string
     */
    protected function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}