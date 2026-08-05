<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use App\Helpers\ApiResponse;

/**
 * Trait HasApiResponse
 * Provides controller-level convenience wrappers around the ApiResponse helper.
 */
trait HasApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Request successful.',
        int $statusCode = 200,
        array $meta = [],
    ): JsonResponse {
        return ApiResponse::success($data, $message, $statusCode, $meta);
    }

    protected function created(mixed $data = null, string $message = 'Resource created successfully.'): JsonResponse
    {
        return ApiResponse::created($data, $message);
    }

    protected function noContent(string $message = 'Resource deleted successfully.'): JsonResponse
    {
        return ApiResponse::noContent($message);
    }

    protected function error(string $message = 'Something went wrong.', int $statusCode = 400, array $errors = []): JsonResponse
    {
        return ApiResponse::error($message, $statusCode, $errors);
    }

    protected function notFound(string $message = 'Resource not found.'): JsonResponse
    {
        return ApiResponse::notFound($message);
    }

    protected function forbidden(string $message = 'Forbidden.'): JsonResponse
    {
        return ApiResponse::forbidden($message);
    }

    protected function paginated(mixed $resource, string $message = 'Request successful.'): JsonResponse
    {
        return ApiResponse::paginated($resource, $message);
    }
}
