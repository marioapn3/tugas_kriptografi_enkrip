<?php

namespace App\Http\Controllers\Transactions\Selling;

use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends AdminBaseController
{
    public function posIndex()
    {
        return Inertia::render($this->source . 'transactions/selling/pos', [
            'title' => 'POS | Jurnalin'
        ]);
    }

    
}
