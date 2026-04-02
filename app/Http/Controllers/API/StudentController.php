<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    /**
     * Get students by subject and class
     */
    public function getStudentsBySubjectAndClass(Request $request): JsonResponse
    {
        try {
            $subjectId = $request->get('subject_id');
            $classId = $request->get('class');
            
            // Validation
            if (!$subjectId || !$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject ID dan Class ID diperlukan'
                ], 400);
            }
            
            // Get students from the specified class
            $students = User::where('role', 'siswa')
                ->where('kelas_id', $classId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'nis', 'kelas_id']);
            
            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada siswa di kelas ini'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'students' => $students,
                'total' => $students->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
