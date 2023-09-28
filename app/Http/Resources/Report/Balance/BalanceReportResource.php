<?php

namespace App\Http\Resources\Report\Balance;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BalanceReportResource extends ResourceCollection
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

        $debit = $journalEntries->reduce(function ($carry, $journal_detail) use ($data) {
            $debit = $journal_detail->debit;
            return $carry + $debit;
        }, 0);

        $credit = $journalEntries->reduce(function ($carry, $journal_detail) use ($data) {
            $credit = $journal_detail->credit;
            return $carry + $credit;
        }, 0);


        return [
            'id' => $data->id,
            'name' => $data->name,
            'code' => $data->code,
            'description' => $data->description,
            'debit' => number_format($debit),
            'credit' => number_format($credit),

            // 'is_profit' =>  intval($totalCredit) > intval($totalDebit) ? "True" : "False",
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
