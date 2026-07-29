<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Admin
        User::updateOrCreate(
            ['email' => 'admin@medicart.com'],
            [
                'name' => 'Store Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Clear Tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        Brand::truncate();
        Product::truncate();
        ProductImage::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Seed Categories
        $categories = [
            ['name' => 'Prescription Medicines', 'description' => 'Medicines requiring a doctor prescription.', 'icon' => 'ph-prescription'],
            ['name' => 'Vitamins & Supplements', 'description' => 'Daily vitamins and dietary supplements.', 'icon' => 'ph-pill'],
            ['name' => 'First Aid', 'description' => 'Bandages, antiseptics, and emergency supplies.', 'icon' => 'ph-first-aid'],
            ['name' => 'Skincare', 'description' => 'Dermatologist approved skincare products.', 'icon' => 'ph-drop'],
            ['name' => 'Baby Care', 'description' => 'Safe products for infants and toddlers.', 'icon' => 'ph-baby'],
            ['name' => 'Medical Devices', 'description' => 'Thermometers, BP monitors, etc.', 'icon' => 'ph-thermometer'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[$cat['name']] = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'status' => 1
            ])->id;
        }

        // 4. Seed Brands
        $brandNames = ['Pfizer', 'Johnson & Johnson', 'Bayer', 'Novartis', 'GSK', 'BioDerma', 'CeraVe', 'Pampers', 'Omron', 'Band-Aid', 'Generic'];
        $brandIds = [];
        foreach ($brandNames as $brand) {
            $brandIds[$brand] = Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'status' => 1
            ])->id;
        }

        // 5. Seed Products + Images, then repair any broken paths
        $this->call([
            ProductSeeder::class,
            ProductImageSeeder::class,
        ]);

        if ($this->command) {
            $this->command->newLine();
            $this->command->info('Seeding complete.');
            $this->command->line('If using local uploads, run: php artisan storage:link');
            $this->command->line('To download images locally, run: php artisan products:download-images');
        }
    }
}
