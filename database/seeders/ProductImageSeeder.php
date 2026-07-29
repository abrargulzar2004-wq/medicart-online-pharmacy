<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    /** Verified working fallback images grouped by category theme. */
    private const FALLBACK_IMAGES = [
        'Prescription Medicines' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop',
        'Vitamins & Supplements' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop',
        'First Aid' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?q=80&w=600&auto=format&fit=crop',
        'Skincare' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop',
        'Baby Care' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop',
        'Medical Devices' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop',
    ];

    private const DEFAULT_FALLBACK = 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop';

    /**
     * Repair or deactivate products with missing, placeholder, or broken image paths.
     */
    public function run(): void
    {
        if (! is_link(public_path('storage')) && ! file_exists(public_path('storage'))) {
            $this->command?->warn('Storage symlink missing. Run: php artisan storage:link');
        }

        $repaired = 0;
        $deactivated = 0;

        Product::with(['images', 'category'])->chunkById(100, function ($products) use (&$repaired, &$deactivated) {
            foreach ($products as $product) {
                if ($product->hasValidImage()) {
                    continue;
                }

                $fallback = self::FALLBACK_IMAGES[$product->category?->name ?? '']
                    ?? self::DEFAULT_FALLBACK;

                $primary = $product->primaryImage();

                if ($primary) {
                    $primary->update(['image_path' => $fallback, 'is_primary' => 1]);
                } else {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $fallback,
                        'is_primary' => 1,
                    ]);
                }

                if ($product->status) {
                    $repaired++;
                    $this->command?->line("Repaired image: {$product->name}");
                    continue;
                }

                $product->update(['status' => 1]);
                $repaired++;
            }
        });

        Product::with('images')->chunkById(100, function ($products) use (&$deactivated) {
            foreach ($products as $product) {
                if ($product->hasValidImage() || ! $product->status) {
                    continue;
                }

                $product->update(['status' => 0]);
                $deactivated++;
                $this->command?->line("Deactivated (unrepairable): {$product->name}");
            }
        });

        ProductImage::query()
            ->where(function ($query) {
                $query->whereNull('image_path')
                    ->orWhere('image_path', '')
                    ->orWhereIn('image_path', ProductImage::INVALID_PATHS);
            })
            ->delete();

        Storage::disk('public')->makeDirectory('products');

        $this->command?->info("Repaired: {$repaired}");
        $this->command?->info("Deactivated: {$deactivated}");
        $this->command?->line('Optional: php artisan products:download-images (local storage)');
    }
}
