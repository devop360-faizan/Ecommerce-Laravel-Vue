<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

class UnauthorizedActionException extends ApiException
{
    public function __construct(string $message = 'You are not authorized to perform this action.')
    {
        parent::__construct(
            message: $message,
            statusCode: Response::HTTP_FORBIDDEN,
        );
    }
}
