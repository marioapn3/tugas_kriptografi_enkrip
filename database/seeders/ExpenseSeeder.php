<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $journal = Journal::create(
            [
                'no_transaction' => 'EJ-1232',
                'description' => 'Pembayaran Listrik Bulan September',
                'date' => date("Y-m-d H:i:s"),
            ]
        );

        $expense =  Expense::create([
            'journal_id' => $journal->id,
            'no_transaction' => 'EJ-1232',
            'date' => date("Y-m-d H:i:s"),
            'payment_account' => 1,
            'description' => 'Pembayaran Listrik Bulan September'
        ]);

       
        $data2 = [
            [
                'expense_id' => $expense->id,
                'expense_account' => 8,
                'description' => "Bayar Listrik",
                'total_expense' => 1000000
            ],
            [
                'expense_id' => $expense->id,
                'expense_account' => 9,
                'description' => "Bayar Sewa Pembangunan",
                'total_expense' => 2000000
            ],
        ];
        foreach ($data2 as $value) {
            try {
                $expenseDetail = ExpenseDetail::updateOrCreate([
                    'expense_id' => $value["expense_id"],
                    'expense_account' => $value["expense_account"],
                    'description' => $value["description"],
                    'total_expense' => $value["total_expense"],

                ]);

                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $expense->payment_account,
                    'debit' => 0,
                    'credit' => $expenseDetail->total_expense,
                    'description' => $expense->description,
                ]);
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $expenseDetail->expense_account,
                    'debit' => $expenseDetail->total_expense,
                    'credit' => 0,
                    'description' => $expense->description,
                ]);
            } catch (\Exception $exception) {
                $this->command->info($exception->getMessage());
                // Do something when the exception
            }
        }
    }
}
