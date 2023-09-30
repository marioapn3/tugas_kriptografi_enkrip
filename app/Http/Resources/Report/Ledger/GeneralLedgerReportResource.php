<?php

namespace App\Http\Resources\Report\Ledger;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GeneralLedgerReportResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Success get report lists",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {

        $start_date = request('start_date'); // Ambil tanggal awal dari request
        $end_date = request('end_date'); // Ambil tanggal akhir dari request

        $journalEntries = $data->journalDetails;

        if ($start_date && $end_date) {

            $journalEntries = $journalEntries->filter(function ($journal_detail) use ($start_date, $end_date) {
                $journalDate = $journal_detail->journal->date;
                return $journalDate >= $start_date && $journalDate <= $end_date;
            });
        }

        $totalAmount = $journalEntries->reduce(function ($carry, $journal_detail) use ($data) {

            if ($data->AccountCategory->classification->debit_or_credit === 'debit') {
                $amount = $journal_detail->debit - $journal_detail->credit;
            } elseif ($data->AccountCategory->classification->debit_or_credit === 'credit') {
                $amount = $journal_detail->credit - $journal_detail->debit;
            }

            return $carry + $amount;
        }, 0);

        return [
            'id' => $data->id,

            'name' => $data->name,
            'code' => $data->code,
            'description' => $data->description,
            'journal_entries' => $journalEntries->map(function ($journal_detail) {
                return [
                    'id' => $journal_detail->id,
                    'transaction_number' => $journal_detail->journal->no_transaction,
                    'date' => $journal_detail->journal->date,
                    'debit' => number_format($journal_detail->debit),
                    'credit' => number_format($journal_detail->credit),
                    'description' => $journal_detail->journal->description,
                ];
            }),
            'total_amount' => number_format($totalAmount),
        ];
    }

    private function transformCollection($collection)
    {
        return $collection->transform(function ($data) {
            return $this->transformData($data);
        });
    }

    private function metaData()
    {
        return [
            "total" => $this->total(),
            "count" => $this->count(),
            "per_page" => (int)$this->perPage(),
            "current_page" => $this->currentPage(),
            "total_pages" => $this->lastPage(),
            "links" => [
                "next" => $this->nextPageUrl()
            ],
        ];
    }
}
