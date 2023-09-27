<?php

namespace App\Http\Resources\Journal;

use Illuminate\Http\Resources\Json\JsonResource;

class JournalDetailResource extends JsonResource
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
                'no_transaction' => $this->no_transaction,
                'date' => $this->date,
                'description' => $this->description,
                'journal_entries' => $this->journalDetails->map(function ($journal_detail) {
                    return [
                        'id' => $journal_detail->id,
                        'account_id' => $journal_detail->account_id,
                        'account_name' => $journal_detail->account->name,
                        'account_code' => $journal_detail->account->code,
                        'debit' => number_format($journal_detail->debit),
                        'credit' => number_format($journal_detail->credit),
                        'description' => $journal_detail->description,
                    ];
                }),
                'total_debit' => number_format($this->journalDetails->sum('debit')),
                'total_credit' => number_format($this->journalDetails->sum('credit')),

            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
                'pagination' => (object)[],
            ],
        ];
    }
}
