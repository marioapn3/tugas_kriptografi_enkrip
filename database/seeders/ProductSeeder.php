<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate 10 produk secara acak
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'code' => $faker->unique()->ean13,
                'name' => $faker->word,
                'description' => $faker->sentence,
                'purchase_price' => $faker->randomFloat(2, 1, 100),
                'purchase_account' => 7,
                'sale_price' => $faker->randomFloat(2, 10, 200),
                'sale_account' => 6,
                'inventory_account' => 3,
                'stock' => $faker->numberBetween(0, 100),
                'unit' => $faker->word,
                'image' => $faker->imageUrl(),
            ]);
        }
    }
}
