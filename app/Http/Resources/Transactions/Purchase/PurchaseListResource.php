<?php

namespace App\Http\Resources\Transactions\Purchase;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseListResource extends ResourceCollection
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
            'supplier' => $data->supplier->name,
            'supplier_id' => $data->supplier_id,
            'total_price' => number_format($data->purchase_details->sum(function ($detail) {
                return $detail->quantity * $detail->price_per_unit;
            })),
            'purchase_details' => $data->purchase_details->map(function ($purchase_detail) {
                return [
                    'product_id' => $purchase_detail->product_id,
                    'quantity' => $purchase_detail->quantity,
                    'price_per_unit' => $purchase_detail->price_per_unit,
                    'total_price' => $purchase_detail->total_price,
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
