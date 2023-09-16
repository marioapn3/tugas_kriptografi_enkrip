<?php

namespace App\Actions\Options;

use App\Models\Classification;


class GetClasificationOptions
{
    public function handle()
    {
        $clasification = Classification::all();

        $new_clasification = [];
        foreach ($clasification as $data) {
            $new_clasification[$data->id] = $data->name . ' - ' . $data->debit_or_credit;
        }
        return $new_clasification;
    }
}
