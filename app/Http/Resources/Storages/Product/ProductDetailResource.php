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
                'transaction_history' => $this->productStock->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'type' => $transaction->type,
                        'qty' => $transaction->quantity,
                        'purchase' => $transaction->journal->purchase ? [
                            'id' => $transaction->journal->purchase->id,
                            'date' => $transaction->journal->purchase->date,
                            'no_transaction' => $transaction->journal->purchase->no_transaction,
                            'description' => $transaction->journal->purchase->description,
                            'supplier' => $transaction->journal->purchase->supplier->name,
                            'details' => $transaction->journal->purchase->purchase_details->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'qty' => $item->quantity,
                                    'price' => number_format($item->price_per_unit),
                                    'total' => number_format((int) $item->price_per_unit * (int) $item->quantity),
                                ];
                            }),
                        ] : null,
                        'sales' => $transaction->journal->sales ? [
                            'id' => $transaction->journal->sales->id,
                            'date' => $transaction->journal->sales->date,
                            'no_transaction' => $transaction->journal->sales->no_transaction,
                            'description' => $transaction->journal->sales->description,
                            'customer' =>  $transaction->journal->sales->customer->name,
                            'details' => $transaction->journal->sales->sale_details->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'qty' => $item->qty,
                                    'price' => number_format($item->price),
                                    'total' => number_format((int) $item->price * (int) $item->qty),
                                ];
                            }),
                        ] : null

                    ];
                }),
                'stock' => $this->productStock->sum(function ($stock) {
                    return $stock->type == 'in' ? $stock->quantity : -$stock->quantity;
                }),
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
