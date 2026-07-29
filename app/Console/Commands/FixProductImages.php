<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FixProductImages extends Command
{
    protected $signature = 'products:fix-images
                            {--repair : Assign a working fallback image URL to invalid products}
                            {--deactivate : Hide invalid products from the storefront (default when not repairing)}
                            {--verify-remote : Verify remote image URLs respond successfully}
                            {--delete-images : Delete invalid product image records instead of deactivating products}';

    protected $description = 'Repair or deactivate products with missing, placeholder, or broken image paths';

    /** Verified working fallback images for pharmacy catalog items. */
    private const FALLBACK_IMAGES = [
        'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop',
    ];

    public function handle(): int
    {
        if (! is_link(public_path('storage')) && ! file_exists(public_path('storage'))) {
            $this->warn('Storage symlink missing. Run: php artisan storage:link');
        }

        $repair = (bool) $this->option('repair');
        $deactivate = $this->option('deactivate') || ! $repair;
        $verifyRemote = (bool) $this->option('verify-remote');
        $deleteImages = (bool) $this->option('delete-images');

        $repaired = 0;
        $deactivated = 0;
        $deletedImages = 0;
        $skipped = 0;

        Product::with('images')->chunkById(100, function ($products) use (
            $repair,
            $deactivate,
            $verifyRemote,
            $deleteImages,
            &$repaired,
            &$deactivated,
            &$deletedImages,
            &$skipped
        ) {
            foreach ($products as $product) {
                $primary = $product->primaryImage();
                $isValid = $this->productImageIsUsable($product, $verifyRemote);

                if ($isValid) {
                    $skipped++;
                    continue;
                }

                if ($repair) {
                    $fallback = self::FALLBACK_IMAGES[$product->id % count(self::FALLBACK_IMAGES)];

                    if ($primary) {
                        $primary->update(['image_path' => $fallback, 'is_primary' => 1]);
                    } else {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $fallback,
                            'is_primary' => 1,
                        ]);
                    }

                    if (! $product->status) {
                        $product->update(['status' => 1]);
                    }

                    $repaired++;
                    $this->line("Repaired image: {$product->name}");
                    continue;
                }

                if ($deleteImages) {
                    $product->images()->delete();
                    $deletedImages++;
                    $this->line("Removed image records: {$product->name}");
                }

                if ($deactivate && $product->status) {
                    $product->update(['status' => 0]);
                    $deactivated++;
                    $this->line("Deactivated product: {$product->name}");
                }
            }
        });

        $this->newLine();
        $this->info("Skipped (valid): {$skipped}");
        $this->info("Repaired: {$repaired}");
        $this->info("Deactivated: {$deactivated}");
        $this->info("Deleted image records: {$deletedImages}");

        return self::SUCCESS;
    }

    private function productImageIsUsable(Product $product, bool $verifyRemote): bool
    {
        $primary = $product->primaryImage();

        if (! $primary || ! ProductImage::isValidPath($primary->image_path, true)) {
            return false;
        }

        $path = trim($primary->image_path);

        if (! $verifyRemote || ! str_starts_with($path, 'http')) {
            return true;
        }

        try {
            $response = Http::timeout(8)->head($path);

            if ($response->successful()) {
                return true;
            }

            $response = Http::timeout(8)->get($path);

            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }
}
