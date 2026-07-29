<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    /** Paths/basenames treated as missing or broken placeholders. */
    public const INVALID_PATHS = [
        '',
        'no-image.png',
        'no_image.png',
        'noimage.png',
        'default.png',
        'placeholder.png',
        'null',
        'none',
    ];

    protected $fillable = ['product_id', 'image_path', 'is_primary'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeValidPath(Builder $query): Builder
    {
        return $query
            ->whereNotNull('image_path')
            ->where('image_path', '!=', '')
            ->whereNotIn('image_path', self::INVALID_PATHS)
            ->where(function (Builder $pathQuery): void {
                foreach (['no-image', 'no_image', 'noimage', 'placeholder'] as $fragment) {
                    $pathQuery->where('image_path', 'not like', '%' . $fragment . '%');
                }
            });
    }

    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', 1);
    }

    public static function isValidPath(?string $path, bool $checkFilesystem = true): bool
    {
        if ($path === null) {
            return false;
        }

        $path = trim($path);

        if ($path === '' || in_array(strtolower($path), array_map('strtolower', self::INVALID_PATHS), true)) {
            return false;
        }

        $basename = strtolower(basename($path));

        if (in_array($basename, array_map('strtolower', self::INVALID_PATHS), true)) {
            return false;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return true;
        }

        if (! $checkFilesystem) {
            return true;
        }

        return Storage::disk('public')->exists(self::storageRelativePath($path));
    }

    public static function storageRelativePath(string $path): string
    {
        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, strlen('storage/'));
        }

        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, strlen('public/'));
        }

        return $normalized;
    }

    public function isValid(bool $checkFilesystem = true): bool
    {
        return self::isValidPath($this->image_path, $checkFilesystem);
    }

    public function url(): string
    {
        if (! self::isValidPath($this->image_path, false)) {
            return asset('images/product-placeholder.svg');
        }

        $path = $this->image_path;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . self::storageRelativePath($path));
    }
}
