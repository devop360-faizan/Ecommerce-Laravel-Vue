<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

class InvalidCouponException extends ApiException
{
    public function __construct(string $reason = 'Invalid or expired coupon code.')
    {
        parent::__construct(
            message: $reason,
            statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
