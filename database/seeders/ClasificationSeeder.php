<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasificationSeeder extends Seeder
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
                'name' => 'Harta',
                'debit_or_credit' => 'debit'

            ],
            [
                'name' => 'Kewajiban',
                'debit_or_credit' => 'credit'
            ],
            [
                'name' => 'Modal',
                'debit_or_credit' => 'credit'

            ], [
                'name' => 'Pendapatan',
                'debit_or_credit' => 'credit'

            ], [
                'name' => 'Beban',
                'debit_or_credit' => 'debit'

            ]
        ];

        // Create role and assign permission to role
        foreach ($data as $value) {
            try {
                Classification::updateOrCreate([
                    'name' => $value["name"],
                    'debit_or_credit' => $value["debit_or_credit"],
                ]);
            } catch (\Exception $exception) {
                $this->command->info($exception->getMessage());
                // Do something when the exception
            }
        }
    }
}
