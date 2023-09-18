<?php

namespace App\Http\Resources\Storages\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
                'description' => $this->description,
                'purchase_price' => number_format($this->purchase_price, 0, ',', '.'),
                'sale_price' => number_format($this->sale_price, 0, ',', '.'),
                'purchase_account' => $this->purchaseAccount,
                'sale_account' => $this->saleAccount,
                'inventory_account' => $this->inventoryAccount,
                'stock' => $this->stock,
                'image' => $this->image,
                'image_preview' => config('app.file_upload_endpoint') . $this->image,
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
                'pagination' => (object)[],
            ],
        ];
    }
}
