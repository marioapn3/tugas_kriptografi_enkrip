<?php

namespace Database\Seeders;

use App\Models\CartSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'deposit_to_account_id' => 1,
        ];

        CartSetting::create($data);
    }
}
