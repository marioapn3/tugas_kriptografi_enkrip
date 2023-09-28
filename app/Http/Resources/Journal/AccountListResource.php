<?php

namespace App\Http\Resources\Journal;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountListResource extends ResourceCollection
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
                "message" => "Success get Account list",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        return [
            'id' => $data->id,
            'account_category_id' => $data->account_category_id,
            'code' => $data->code,
            'name' => $data->name,
            'account_category' => $data->AccountCategory,
            'balance' => number_format($this->getTotalAmount($data))
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
