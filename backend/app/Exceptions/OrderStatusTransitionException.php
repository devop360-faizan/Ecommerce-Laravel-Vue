<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

class OrderStatusTransitionException extends ApiException
{
    public function __construct(string $from, string $to)
    {
        parent::__construct(
            message: "Cannot transition order status from '{$from}' to '{$to}'.",
            statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
