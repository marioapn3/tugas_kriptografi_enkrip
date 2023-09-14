<?php

namespace App\Http\Controllers\Contacts;

use App\Actions\Utility\Contact\GetContactMenuAction;
use App\Http\Controllers\AdminBaseController;
use App\Http\Resources\Contacts\Customer\CustomerListResource;
use App\Services\Contacts\ContactService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends AdminBaseController
{
    public function __construct(GetContactMenuAction $getContactMenuAction, ContactService $contactService)
    {
        $this->contactService = $contactService;
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
        try {
            $data = $this->contactService->getData($request);

            $result = new CustomerListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
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
