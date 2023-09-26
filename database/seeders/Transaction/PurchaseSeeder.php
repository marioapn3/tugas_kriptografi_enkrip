<?php

namespace Database\Seeders\Transaction;

use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Journal::create([
            'id' => 5,
            'no_transaction' => 'J-242424',
            'date' => date("Y-m-d H:i:s"),
            'description' => 'Testing Purchased'
        ]);
        Purchase::create([
            'id' => 3,
            'date' => date("Y-m-d H:i:s"),
            'no_transaction' => '1',
            'supplier_id' => 1,
            'journal_id' => 5,
        ]);
        PurchaseDetail::create([
            'purchase_id' => 3,
            'product_id' => 1,
            'quantity' => 5,
            'price_per_unit' => 2000,
            'total_price' => 10000,
        ]);

        $data = [
            [
                'journal_id' => 5,
                'account_id' => 1,
                'debit' => 0,
                'kredit' => 20000,
            ],
            [
                'journal_id' => 5,
                'account_id' => 3,
                'kredit' => 0,
                'debit' => 20000,
            ],
        ];

        // Create role and assign permission to role
        foreach ($data as $value) {
            try {
                JournalDetail::updateOrCreate([
                    'journal_id' => $value["journal_id"],
                    'account_id' => $value["account_id"],
                    'credit' => $value["kredit"],
                    'debit' => $value["debit"],
                ]);
            } catch (\Exception $exception) {
                $this->command->info($exception->getMessage());
            }
        }
    }
}
