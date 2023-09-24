<?php

namespace App\Actions\Options;

use App\Models\Contact;

class GetSupplierOptions
{
    public function handle()
    {
        $supplier = Contact::where('type', 'supplier')->get();
        $new_supplier = [];
        foreach ($supplier as $data) {
            $new_supplier[$data->id] = $data->name;
        }
        return $new_supplier;
    }
}
