<?php

namespace App\Http\Controllers\Contacts;

use App\Actions\Utility\Contact\GetContactMenuAction;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends AdminBaseController
{
    public function __construct(GetContactMenuAction $getContactMenuAction)
    {
        $this->getContactMenuAction = $getContactMenuAction;
    }

    public function customerIndex()
    {
        return Inertia::render($this->source . 'contacts/customer/index', [
            "title" => 'Customer | Jurnalin',
            "additional" => [
                "menu" => $this->getContactMenuAction->handle()
            ],
        ]);
    }

    public function getData(Request $request)
    {
    }

    public function createData(Request $request)
    {
    }

    public function updateData(Request $request)
    {
    }

    public function deleteData(Request $request)
    {
    }
}
