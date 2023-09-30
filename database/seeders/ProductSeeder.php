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

        $data = [
            [
                'code' => 'ITEM-' . $faker->unique(false, 10)->ean13,
                'name' => 'Laptop Asus ROG',
                'description' => '-',
                'purchase_price' => 10000000,
                'purchase_account' => 7,
                'sale_price' => 15000000,
                'sale_account' => 6,
                'inventory_account' => 3,
            ],
            [
                'code' => 'ITEM-' . $faker->unique(false, 10)->ean13,
                'name' => 'Laptop Asus Zenbook',
                'description' => '-',
                'purchase_price' => 11000000,
                'purchase_account' => 7,
                'sale_price' => 13000000,
                'sale_account' => 6,
                'inventory_account' => 3,
            ],
            [
                'code' => 'ITEM-' . $faker->unique(false, 10)->ean13,
                'name' => 'Laptop Asus Vivobook',
                'description' => '-',
                'purchase_price' => 9000000,
                'purchase_account' => 7,
                'sale_price' => 11000000,
                'sale_account' => 6,
                'inventory_account' => 3,
            ],
            [
                'code' => 'ITEM-' . $faker->unique(false, 10)->ean13,
                'name' => 'Laptop Asus TUF',
                'description' => '-',
                'purchase_price' => 15000000,
                'purchase_account' => 7,
                'sale_price' => 17500000,
                'sale_account' => 6,
                'inventory_account' => 3,
            ],
            [
                'code' => 'ITEM-' . $faker->unique(false, 10)->ean13,
                'name' => 'Laptop Asus Expertbook',
                'description' => '-',
                'purchase_price' => 20000000,
                'purchase_account' => 7,
                'sale_price' => 22000000,
                'sale_account' => 6,
                'inventory_account' => 3,
            ],
        ];

        foreach ($data as $key => $value) {
            Product::create($value);
        }
    }
}
