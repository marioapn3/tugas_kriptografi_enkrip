<?php

namespace App\Http\Resources\Transactions\Selling;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleListResource extends ResourceCollection
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
                "message" => "Success get sales list",
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
            'customer_id' => $data->customer_id,
            'customer_name' => $data->customer->name,
            'account_id' => $data->deposit_to_account_id,
            // get total from sale details with calculate qty * price to get total price
            'total_price' => number_format($data->sale_details->sum(function ($detail) {
                return $detail->qty * $detail->price;
            })),
            'product_entries' => $data->sale_details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'product_id' => $detail->product_id,
                    'qty' => (int)$detail->qty,
                    'price' => (int)$detail->price,
                    'total' => number_format($detail->qty * $detail->price),
                ];
            }),
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
