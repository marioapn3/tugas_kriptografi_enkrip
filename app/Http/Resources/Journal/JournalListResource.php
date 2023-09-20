<?php

namespace App\Http\Resources\Journal;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JournalListResource extends ResourceCollection
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
                "message" => "Success get jounals list",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        return [
            'id' => $data->id,
            'no_transaction' => $data->no_transaction,
            'date' => $data->date,
            'description' => $data->description,
            'journal_entries' => $data->journalDetails->map(function ($journal_detail) {
                return [
                    'id' => $journal_detail->id,
                    'account_id' => $journal_detail->account_id,
                    'account_name' => $journal_detail->account->name,
                    'debit' => number_format($journal_detail->debit),
                    'credit' => number_format($journal_detail->credit),
                    'description' => $journal_detail->description,
                ];
            })
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
