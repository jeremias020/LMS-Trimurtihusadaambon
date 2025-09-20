<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Handle API success response
     */
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Handle API error response
     */
    protected function errorResponse(string $message = 'Error', $errors = null, int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Handle validation errors
     */
    protected function handleValidationError(ValidationException $e): JsonResponse
    {
        return $this->errorResponse(
            'Validation failed',
            $e->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Handle general exceptions
     */
    protected function handleException(\Exception $e, string $context = 'Controller'): JsonResponse
    {
        Log::error("{$context} Error: " . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);

        $message = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan internal server';
        
        return $this->errorResponse(
            $message,
            null,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Handle not found errors
     */
    protected function notFoundResponse(string $message = 'Data tidak ditemukan'): JsonResponse
    {
        return $this->errorResponse($message, null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Handle unauthorized errors
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Handle forbidden errors
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, null, Response::HTTP_FORBIDDEN);
    }

    /**
     * Validate request with custom error handling
     */
    protected function validateRequest(Request $request, array $rules, array $messages = []): array
    {
        try {
            return $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * Log activity
     */
    protected function logActivity(string $action, array $data = []): void
    {
        Log::info("Activity: {$action}", array_merge($data, [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]));
    }
}