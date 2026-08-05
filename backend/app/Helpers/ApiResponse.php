<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Centralized API response helper.
 * Use via the HasApiResponse trait in controllers.
 */
final class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Request successful.',
        int $statusCode = Response::HTTP_OK,
        array $meta = [],
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
        ];

        if (! is_null($data)) {
            $payload['data'] = $data;
        }

        if (! empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $statusCode);
    }

    public static function created(
        mixed $data = null,
        string $message = 'Resource created successfully.',
    ): JsonResponse {
        return self::success($data, $message, Response::HTTP_CREATED);
    }

    public static function noContent(string $message = 'Resource deleted successfully.'): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message], Response::HTTP_OK);
    }

    public static function error(
        string $message = 'Something went wrong.',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        array $errors = [],
    ): JsonResponse {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if (! empty($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $statusCode);
    }

    public static function unauthorized(string $message = 'Unauthenticated.'): JsonResponse
    {
        return self::error($message, Response::HTTP_UNAUTHORIZED);
    }

    public static function forbidden(string $message = 'Forbidden.'): JsonResponse
    {
        return self::error($message, Response::HTTP_FORBIDDEN);
    }

    public static function notFound(string $message = 'Resource not found.'): JsonResponse
    {
        return self::error($message, Response::HTTP_NOT_FOUND);
    }

    public static function validationError(array $errors, string $message = 'Validation failed.'): JsonResponse
    {
        return self::error($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    public static function paginated(mixed $resource, string $message = 'Request successful.'): JsonResponse
    {
        $data = $resource->response()->getData(true);

        return response()->json([
            'success'    => true,
            'message'    => $message,
            'data'       => $data['data'],
            'pagination' => [
                'total'        => $data['meta']['total'],
                'per_page'     => $data['meta']['per_page'],
                'current_page' => $data['meta']['current_page'],
                'last_page'    => $data['meta']['last_page'],
                'from'         => $data['meta']['from'],
                'to'           => $data['meta']['to'],
            ],
        ]);
    }
}
