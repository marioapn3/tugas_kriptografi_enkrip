<?php

namespace App\Http\Resources\Journal;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountDetailResource extends JsonResource
{
    private $message;

    public function __construct($resource, $message)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->message = $message;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id' => $this->id,
                'code' => $this->code,
                'name' => $this->name,
                'category' => $this->AccountCategory,
                'classification' => $this->AccountCategory->classification,
                'transaction' => $this->journalDetails->map(function ($data) {
                    return [
                        'date' => $data->journal->date,
                        'no_transaction' => $data->journal->purchase ? $data->journal->purchase->no_transaction : ($data->journal->sales ? $data->journal->sales->no_transaction : ($data->journal->expense ? $data->journal->expense->no_transaction : $data->journal->no_transaction)),
                        'contact' => $data->journal->purchase ? $data->journal->purchase->supplier->name : ($data->journal->sales ? $data->journal->sales->customer->name : ''),
                        'description' => $data->journal->description,
                        'debit' => number_format($data->debit),
                        'credit' => number_format($data->credit),
                    ];
                }),
                'total' => number_format($this->getTotalAmount($this))
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
                'pagination' => (object)[],
            ],
        ];
    }

    private function getTotalAmount($data)
    {
        $total = 0;

        foreach ($data->journalDetails as $journal_detail) {
            if ($data->AccountCategory->classification->debit_or_credit === 'debit') {
                $amount = $journal_detail->debit - $journal_detail->credit;
            } elseif ($data->AccountCategory->classification->debit_or_credit === 'credit') {
                $amount = $journal_detail->credit - $journal_detail->debit;
            }
            $total += $amount;
        }
        return $total;
    }
}
