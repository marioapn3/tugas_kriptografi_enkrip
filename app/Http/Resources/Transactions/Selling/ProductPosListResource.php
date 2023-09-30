<?php

namespace App\Http\Resources\Transactions\Selling;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductPosListResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Success get product list",
            ]
        ];
    }

    private function transformData($data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'code' => $data->code,
            'description' => $data->description,
            'purchase_price' => number_format($data->purchase_price, 0, ',', '.'),
            'sale_price' => number_format($data->sale_price, 0, ',', '.'),
            //  make stock if type out is negative and type in is positive
            'stock' => $data->productStock->sum(function ($stock) {
                return $stock->type == 'in' ? $stock->quantity : -$stock->quantity;
            }),
            'purchase_account' => $data->purchase_account,
            'sale_account' => $data->sale_account,
            'inventory_account' => $data->inventory_account,
            'image' => $data->image,
            'image_preview' => config('app.file_upload_endpoint') . $data->image,
        ];
    }

    private function transformCollection($collection)
    {
        return $collection->transform(function ($data) {
            return $this->transformData($data);
        });
    }
}
