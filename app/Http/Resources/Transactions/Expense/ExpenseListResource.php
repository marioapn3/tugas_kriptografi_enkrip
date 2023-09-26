<?php

namespace App\Http\Resources\Transactions\Expense;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseListResource extends ResourceCollection
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
                "message" => "Success get purchase list",
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
            'journal_id' => $data->journal_id,
            'total_price' => number_format($data->expense_details->sum(function ($detail) {
                return $detail->total_expense;
            })),
            'expense_details' => $data->expense_details->map(function ($expense_detail) {
                return [
                    'description' => $expense_detail->description,
                    'expense_account' => $expense_detail->expense_account,
                    'expense' => number_format($expense_detail->total_expense),
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
