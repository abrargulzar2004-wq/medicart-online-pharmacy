<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadProductImages extends Command
{
    protected $signature = 'products:download-images {--force : Re-download even if local file exists}';

    protected $description = 'Download product images locally and update database paths';

    /** Verified working remote URLs grouped by product category theme. */
    private const CATEGORY_IMAGES = [
        'Prescription Medicines' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop',
        'Vitamins & Supplements' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop',
        'First Aid' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?q=80&w=600&auto=format&fit=crop',
        'Skincare' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop',
        'Baby Care' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop',
        'Medical Devices' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop',
    ];

    private const DEFAULT_FALLBACK = 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop';

    public function handle(): int
    {
        if (! is_link(public_path('storage')) && ! file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        Storage::disk('public')->makeDirectory('products');

        $downloaded = 0;
        $skipped = 0;
        $failed = 0;

        Product::with(['images', 'category'])->chunkById(50, function ($products) use (&$downloaded, &$skipped, &$failed) {
            foreach ($products as $product) {
                $primary = $product->primaryImage();
                $localPath = 'products/product-' . $product->id . '.jpg';
                $fullLocal = Storage::disk('public')->path($localPath);

                if (! $this->option('force') && Storage::disk('public')->exists($localPath) && filesize($fullLocal) > 1000) {
                    if ($primary && $primary->image_path !== $localPath) {
                        $primary->update(['image_path' => $localPath, 'is_primary' => 1]);
                    }
                    $skipped++;
                    continue;
                }

                $sourceUrl = $this->resolveSourceUrl($product, $primary);

                if ($this->downloadImage($sourceUrl, $fullLocal)) {
                    if ($primary) {
                        $primary->update(['image_path' => $localPath, 'is_primary' => 1]);
                    } else {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $localPath,
                            'is_primary' => 1,
                        ]);
                    }

                    if (! $product->status) {
                        $product->update(['status' => 1]);
                    }

                    $downloaded++;
                    $this->line("Downloaded: {$product->name}");
                } else {
                    $failed++;
                    $this->error("Failed: {$product->name}");
                }
            }
        });

        $this->newLine();
        $this->info("Downloaded: {$downloaded}");
        $this->info("Skipped (already local): {$skipped}");
        $this->info("Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function resolveSourceUrl(Product $product, ?ProductImage $primary): string
    {
        $path = trim($primary?->image_path ?? '');

        if ($path !== '' && ! str_starts_with($path, 'http') && Storage::disk('public')->exists(ProductImage::storageRelativePath($path))) {
            return Storage::disk('public')->path(ProductImage::storageRelativePath($path));
        }

        if ($path !== '' && str_starts_with($path, 'http') && $this->urlIsReachable($path)) {
            return $path;
        }

        $categoryName = $product->category?->name ?? '';

        return self::CATEGORY_IMAGES[$categoryName] ?? self::DEFAULT_FALLBACK;
    }

    private function urlIsReachable(string $url): bool
    {
        try {
            $response = Http::timeout(10)->head($url);

            if ($response->successful()) {
                return true;
            }

            $response = Http::timeout(10)->get($url);

            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    private function downloadImage(string $source, string $destination): bool
    {
        try {
            if (str_starts_with($source, 'http')) {
                $response = Http::timeout(30)->get($source);

                if (! $response->successful()) {
                    return false;
                }

                $content = $response->body();
            } else {
                if (! file_exists($source)) {
                    return false;
                }

                $content = file_get_contents($source);
            }

            if (strlen($content) < 500) {
                return false;
            }

            file_put_contents($destination, $content);

            return file_exists($destination) && filesize($destination) > 500;
        } catch (\Throwable) {
            return false;
        }
    }
}
