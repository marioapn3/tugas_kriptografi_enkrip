<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Transaction\PurchaseSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ClasificationSeeder::class,
            AccountCategorySeeder::class,
            AccountSeeder::class,
            ProductSeeder::class,
            ContactSeeder::class,
            // PurchaseSeeder::class,
            ExpenseSeeder::class
        ]);
    }
}
