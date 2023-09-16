<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
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
                'classification_id' => 1,
                'code' => '1-1',
                'name' => 'Asset'

            ],
            [
                'classification_id' => 1,
                'code' => '1-2',
                'name' => 'Piutang Usaha'

            ], [
                'classification_id' => 1,
                'code' => '1-3',
                'name' => 'Perlengkapan'

            ], [
                'classification_id' => 1,
                'code' => '1-4',
                'name' => 'Persediaan'

            ], [
                'classification_id' => 2,
                'code' => '2-1',
                'name' => 'Utang Usaha'

            ],
            [
                'classification_id' => 3,
                'code' => '3-1',
                'name' => 'Modal Usaha'

            ],
            [
                'classification_id' => 4,
                'code' => '4-1',
                'name' => 'Pendapatan Penjualan'

            ],
            [
                'classification_id' => 5,
                'code' => '5-1',
                'name' => 'Beban'

            ],
        ];

        // Create role and assign permission to role
        foreach ($data as $value) {
            try {
                AccountCategory::updateOrCreate([
                    'classification_id' => $value["classification_id"],
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
