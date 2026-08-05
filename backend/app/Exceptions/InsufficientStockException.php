<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

class InsufficientStockException extends ApiException
{
    public function __construct(string $productName, int $available)
    {
        parent::__construct(
            message: "Insufficient stock for '{$productName}'. Only {$available} unit(s) available.",
            statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
