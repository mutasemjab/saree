<?php

namespace App\Traits;

trait ApiResponseTrait
{
    /**
     * Success Response
     */
    public function successResponse($message = 'Success', $data = null, $code = 200)
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Error Response
     */
    public function errorResponse($message = 'Error', $errors = null, $code = 400)
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation Error Response
     */
    public function validationErrorResponse($errors)
    {
        return $this->errorResponse('Validation Error', $errors, 422);
    }

    /**
     * Not Found Response
     */
    public function notFoundResponse($message = 'Not Found')
    {
        return $this->errorResponse($message, null, 404);
    }

    /**
     * Unauthorized Response
     */
    public function unauthorizedResponse($message = 'Unauthorized')
    {
        return $this->errorResponse($message, null, 401);
    }

    /**
     * Forbidden Response
     */
    public function forbiddenResponse($message = 'Forbidden')
    {
        return $this->errorResponse($message, null, 403);
    }

    /**
     * Server Error Response
     */
    public function serverErrorResponse($message = 'Server Error')
    {
        return $this->errorResponse($message, null, 500);
    }
}