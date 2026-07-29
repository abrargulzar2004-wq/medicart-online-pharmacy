<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'description', 'ingredients',
        'dosage', 'manufacturer', 'manufacturing_date', 'expiry_date', 'price', 'discount_price',
        'stock_quantity', 'sku', 'batch_number', 'barcode', 'requires_prescription', 'status',
        'is_featured', 'is_new_arrival', 'is_best_seller'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'requires_prescription' => 'boolean',
        'status' => 'boolean',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function primaryImage() { return $this->images()->where('is_primary', 1)->first() ?? $this->images()->first(); }

    /**
     * Products that have at least one non-empty, non-placeholder image record.
     * When $checkFilesystem is true, local paths must exist on the public disk.
     */
    public function scopeWithValidImage(Builder $query, bool $checkFilesystem = false): Builder
    {
        $query->whereHas('images', function (Builder $imageQuery): void {
            $imageQuery->validPath()->where(function (Builder $primaryQuery): void {
                $primaryQuery
                    ->where('is_primary', 1)
                    ->orWhereNotExists(function ($subQuery): void {
                        $subQuery->selectRaw('1')
                            ->from('product_images as pi2')
                            ->whereColumn('pi2.product_id', 'product_images.product_id')
                            ->where('pi2.is_primary', 1)
                            ->whereNotNull('pi2.image_path')
                            ->where('pi2.image_path', '!=', '');
                    });
            });
        });

        if (! $checkFilesystem) {
            return $query;
        }

        $validIds = static::query()
            ->whereHas('images', fn (Builder $imageQuery) => $imageQuery->validPath())
            ->with('images')
            ->get()
            ->filter(fn (Product $product) => $product->hasValidImage())
            ->pluck('id');

        return $query->whereIn('products.id', $validIds->isEmpty() ? [0] : $validIds);
    }

    /**
     * Active storefront catalog products with a resolvable image.
     */
    public function scopeVisibleInStorefront(Builder $query): Builder
    {
        return $query->where('status', 1)->withValidImage(checkFilesystem: true);
    }

    public function hasValidImage(bool $checkFilesystem = true): bool
    {
        $image = $this->primaryImage();

        if (! $image) {
            return false;
        }

        return $image->isValid($checkFilesystem);
    }

    public function imageUrl(): string
    {
        if (! $this->hasValidImage()) {
            return asset('images/product-placeholder.svg');
        }

        $image = $this->primaryImage();
        $path = $image->image_path;

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . ProductImage::storageRelativePath($path));
    }

    public function reviews() { return $this->hasMany(Review::class); }
}