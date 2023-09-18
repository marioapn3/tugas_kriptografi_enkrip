<?php

namespace App\Http\Resources\Storages\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductListResource extends ResourceCollection
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
                'pagination' => $this->metaData()
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
            'stock' => $data->stock,
            'purchase_account' => $data->purchase_account,
            'sale_account' => $data->sale_account,
            'inventory_account' => $data->inventory_account,
            'image_preview' => config('app.file_upload_endpoint') . $data->image,
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
