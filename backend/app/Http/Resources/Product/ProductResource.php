<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'final_price' => $this->final_price,
            'thumbnail' => $this->thumbnail,
            'stock' => $this->stock,
            'is_featured' => $this->is_featured,
            'rating' => $this->reviews_avg_rating ?? 0,
            'reviews_count' => $this->reviews_count ?? 0,
        ];
    }
}
