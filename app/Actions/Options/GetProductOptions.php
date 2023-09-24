<?php

namespace App\Actions\Options;

use App\Models\Product;

class GetProductOptions
{
    public function handle()
    {
        $product = Product::all();

        $new_product = [];
        foreach ($product as $data) {
            $new_product[$data->id] = $data->name;
        }

        return $new_product;
    }
}
