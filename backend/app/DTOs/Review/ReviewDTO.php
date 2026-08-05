<?php

declare(strict_types=1);

namespace App\DTOs\Review;

readonly class ReviewDTO
{
    public function __construct(
        public int $rating,
        public string $comment,
        public ?string $title = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            rating: (int) $data['rating'],
            comment: $data['comment'],
            title: $data['title'] ?? null,
        );
    }
}
