<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasSlug
 * Auto-generates a unique slug from the model's sluggable column on create.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model): void {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->{$model->getSlugSourceColumn()});
            }
        });

        static::updating(function ($model): void {
            if ($model->isDirty($model->getSlugSourceColumn()) && empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->{$model->getSlugSourceColumn()});
            }
        });
    }

    /**
     * The column to generate a slug from. Override in model if needed.
     */
    public function getSlugSourceColumn(): string
    {
        return 'name';
    }

    public static function generateUniqueSlug(string $value): string
    {
        $slug  = Str::slug($value);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
