<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Verified working Unsplash URLs for pharmacy catalog items.
     */
    public static function catalogData(): array
    {
        return [
            ['cat' => 'Prescription Medicines', 'brand' => 'Pfizer', 'name' => 'Amoxicillin 500mg Capsules', 'price' => 12.99, 'rx' => 1, 'stock' => 150, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Prescription Medicines', 'brand' => 'Novartis', 'name' => 'Lisinopril 10mg Tablets', 'price' => 18.50, 'rx' => 1, 'stock' => 200, 'img' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Prescription Medicines', 'brand' => 'GSK', 'name' => 'Ventolin HFA Inhaler', 'price' => 45.00, 'rx' => 1, 'stock' => 75, 'img' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Prescription Medicines', 'brand' => 'Bayer', 'name' => 'Ciprofloxacin 250mg', 'price' => 22.00, 'rx' => 1, 'stock' => 120, 'img' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Prescription Medicines', 'brand' => 'Pfizer', 'name' => 'Lipitor (Atorvastatin) 20mg', 'price' => 35.99, 'rx' => 1, 'stock' => 300, 'img' => 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Prescription Medicines', 'brand' => 'Generic', 'name' => 'Metformin 500mg', 'price' => 8.50, 'rx' => 1, 'stock' => 500, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Prescription Medicines', 'brand' => 'Generic', 'name' => 'Omeprazole 20mg', 'price' => 14.00, 'rx' => 1, 'stock' => 180, 'img' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Prescription Medicines', 'brand' => 'Generic', 'name' => 'Sertraline 50mg', 'price' => 16.75, 'rx' => 1, 'stock' => 90, 'img' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Generic', 'name' => 'Vitamin C 1000mg with Zinc', 'price' => 15.99, 'rx' => 0, 'stock' => 400, 'img' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Generic', 'name' => 'Omega-3 Fish Oil 1200mg', 'price' => 24.50, 'rx' => 0, 'stock' => 250, 'img' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Bayer', 'name' => 'One A Day Multivitamin Men', 'price' => 19.99, 'rx' => 0, 'stock' => 150, 'img' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Bayer', 'name' => 'One A Day Multivitamin Women', 'price' => 19.99, 'rx' => 0, 'stock' => 150, 'img' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Generic', 'name' => 'Vitamin D3 5000 IU', 'price' => 12.50, 'rx' => 0, 'stock' => 300, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Generic', 'name' => 'Magnesium Citrate 250mg', 'price' => 14.25, 'rx' => 0, 'stock' => 200, 'img' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Generic', 'name' => 'Melatonin 5mg Sleep Aid', 'price' => 9.99, 'rx' => 0, 'stock' => 500, 'img' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Vitamins & Supplements', 'brand' => 'Generic', 'name' => 'Iron Supplement 65mg', 'price' => 8.99, 'rx' => 0, 'stock' => 100, 'img' => 'https://images.unsplash.com/photo-1577401239170-897942555fb3?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Band-Aid', 'name' => 'Adhesive Bandages Variety Pack', 'price' => 6.50, 'rx' => 0, 'stock' => 1000, 'img' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Johnson & Johnson', 'name' => 'First Aid Kit (140 Piece)', 'price' => 29.99, 'rx' => 0, 'stock' => 50, 'img' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Generic', 'name' => 'Hydrogen Peroxide 3%', 'price' => 3.99, 'rx' => 0, 'stock' => 200, 'img' => 'https://images.unsplash.com/photo-1583324113626-70df0f4deaab?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Generic', 'name' => 'Rubbing Alcohol 70%', 'price' => 4.50, 'rx' => 0, 'stock' => 250, 'img' => 'https://images.unsplash.com/photo-1583324113626-70df0f4deaab?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Band-Aid', 'name' => 'Gauze Pads 4x4 (25 Pack)', 'price' => 8.99, 'rx' => 0, 'stock' => 300, 'img' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Generic', 'name' => 'Medical Tape 1 Inch', 'price' => 2.99, 'rx' => 0, 'stock' => 400, 'img' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Generic', 'name' => 'Antibiotic Ointment (Triple)', 'price' => 7.50, 'rx' => 0, 'stock' => 150, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'First Aid', 'brand' => 'Generic', 'name' => 'Instant Cold Pack (2 Pack)', 'price' => 5.99, 'rx' => 0, 'stock' => 200, 'img' => 'https://images.unsplash.com/photo-1584017911766-d451b3d0e843?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'CeraVe', 'name' => 'Hydrating Facial Cleanser', 'price' => 14.99, 'rx' => 0, 'stock' => 120, 'img' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'BioDerma', 'name' => 'Sensibio H2O Micellar Water', 'price' => 16.50, 'rx' => 0, 'stock' => 90, 'img' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'CeraVe', 'name' => 'Daily Moisturizing Lotion', 'price' => 13.99, 'rx' => 0, 'stock' => 150, 'img' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'Generic', 'name' => 'Sunscreen SPF 50 Broad Spectrum', 'price' => 11.99, 'rx' => 0, 'stock' => 200, 'img' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'Generic', 'name' => 'Acne Spot Treatment 10% Benzoyl', 'price' => 9.50, 'rx' => 0, 'stock' => 80, 'img' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'BioDerma', 'name' => 'Atoderm Intensive Baume', 'price' => 22.00, 'rx' => 0, 'stock' => 60, 'img' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'Generic', 'name' => 'Vitamin C Serum 20%', 'price' => 28.00, 'rx' => 0, 'stock' => 100, 'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Skincare', 'brand' => 'Generic', 'name' => 'Hyaluronic Acid Face Cream', 'price' => 19.99, 'rx' => 0, 'stock' => 110, 'img' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Pampers', 'name' => 'Swaddlers Diapers Size 1 (100 Ct)', 'price' => 32.99, 'rx' => 0, 'stock' => 400, 'img' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Johnson & Johnson', 'name' => 'Baby Shampoo Tear-Free 27oz', 'price' => 8.99, 'rx' => 0, 'stock' => 300, 'img' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Generic', 'name' => 'Diaper Rash Cream (Zinc Oxide)', 'price' => 7.50, 'rx' => 0, 'stock' => 150, 'img' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Johnson & Johnson', 'name' => 'Baby Lotion Pink 27oz', 'price' => 8.99, 'rx' => 0, 'stock' => 250, 'img' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Pampers', 'name' => 'Sensitive Baby Wipes (336 Ct)', 'price' => 12.99, 'rx' => 0, 'stock' => 500, 'img' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Generic', 'name' => 'Infant Acetaminophen Drops', 'price' => 9.99, 'rx' => 0, 'stock' => 120, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Generic', 'name' => 'Gripe Water for Colic', 'price' => 11.50, 'rx' => 0, 'stock' => 80, 'img' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Baby Care', 'brand' => 'Generic', 'name' => 'Nasal Aspirator Kit', 'price' => 15.00, 'rx' => 0, 'stock' => 60, 'img' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Omron', 'name' => 'Digital Blood Pressure Monitor', 'price' => 45.00, 'rx' => 0, 'stock' => 30, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Generic', 'name' => 'Infrared Forehead Thermometer', 'price' => 29.99, 'rx' => 0, 'stock' => 100, 'img' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Generic', 'name' => 'Fingertip Pulse Oximeter', 'price' => 18.50, 'rx' => 0, 'stock' => 200, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Generic', 'name' => 'Digital Weight Scale', 'price' => 25.00, 'rx' => 0, 'stock' => 50, 'img' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Generic', 'name' => 'Nebulizer Machine Kit', 'price' => 35.99, 'rx' => 1, 'stock' => 40, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Omron', 'name' => 'Pedometer Step Counter', 'price' => 19.99, 'rx' => 0, 'stock' => 80, 'img' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Generic', 'name' => 'CPAP Mask Replacement', 'price' => 55.00, 'rx' => 1, 'stock' => 25, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop'],
            ['cat' => 'Medical Devices', 'brand' => 'Generic', 'name' => 'Heating Pad (Electric)', 'price' => 22.50, 'rx' => 0, 'stock' => 150, 'img' => 'https://images.unsplash.com/photo-1603398938378-e54eab446dde?q=80&w=600&auto=format&fit=crop'],
        ];
    }

    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'name');
        $brandIds = Brand::pluck('id', 'name');

        foreach (self::catalogData() as $item) {
            if (empty($item['img']) || ! ProductImage::isValidPath($item['img'], false)) {
                continue;
            }

            if (! isset($categoryIds[$item['cat']], $brandIds[$item['brand']])) {
                continue;
            }

            $product = Product::create([
                'category_id' => $categoryIds[$item['cat']],
                'brand_id' => $brandIds[$item['brand']],
                'name' => $item['name'],
                'slug' => Str::slug($item['name']) . '-' . rand(1000, 9999),
                'description' => "Professional grade {$item['name']} manufactured by {$item['brand']}. Highly recommended by healthcare professionals for optimal results. Ensure you read the label before usage.",
                'price' => $item['price'],
                'stock_quantity' => $item['stock'],
                'sku' => strtoupper(substr($item['brand'], 0, 3)) . '-' . rand(10000, 99999),
                'batch_number' => 'BCH-' . rand(1000, 9999),
                'requires_prescription' => $item['rx'],
                'is_featured' => rand(1, 10) > 7 ? 1 : 0,
                'is_new_arrival' => rand(1, 10) > 8 ? 1 : 0,
                'is_best_seller' => rand(1, 10) > 7 ? 1 : 0,
                'status' => 1,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => trim($item['img']),
                'is_primary' => 1,
            ]);
        }
    }
}
