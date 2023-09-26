<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'account_category_id' => 1,
                'code' => '1-1001',
                'name' => 'Asset Kas Perusahaan'
            ],
            [
                'account_category_id' => 2,
                'code' => '1-2001',
                'name' => 'Piutang Penjualan Barang'

            ], [
                'account_category_id' => 4,
                'code' => '1-4001',
                'name' => 'Persediaan Dagangan'

            ],
            [
                'account_category_id' => 5,
                'code' => '2-1001',
                'name' => 'Utang Perusahaan Ban'

            ],
            [
                'account_category_id' => 6,
                'code' => '3-1001',
                'name' => 'Modal Uang Owner'

            ],
            [
                'account_category_id' => 7,
                'code' => '4-1001',
                'name' => 'Pendapatan Penjualan Barang Dagangan'

            ],
            [
                'account_category_id' => 8,
                'code' => '5-1001',
                'name' => 'Beban Pokok Pendapatan'

            ],
            [
                'account_category_id' => 8,
                'code' => '5-1002',
                'name' => 'Biaya Listrik'

            ],
            [
                'account_category_id' => 8,
                'code' => '5-1003',
                'name' => 'Biaya Sewa Bangunan'

            ],
        ];

        // Create role and assign permission to role
        foreach ($data as $value) {
            try {
                Account::updateOrCreate([
                    'account_category_id' => $value["account_category_id"],
                    'name' => $value["name"],
                    'code' => $value["code"],
                ]);
            } catch (\Exception $exception) {
                $this->command->info($exception->getMessage());
                // Do something when the exception
            }
        }
    }
}
