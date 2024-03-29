<?php

namespace App\Http\Controllers\Contacts;

use App\Actions\Utility\Contact\GetContactMenuAction;
use App\Enum\Contacts\ContactType;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Contacts\ContactRequest;
use App\Http\Requests\Contacts\UpdateContactRequest;
use App\Http\Resources\Contacts\Customer\CustomerListResource;
use App\Http\Resources\SubmitDefaultResource;
use App\Models\Contact;
use App\Services\Contacts\ContactService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends AdminBaseController
{

    // This not required for defining
    private $contactService;
    private $typeCustomer;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;

        $this->typeCustomer = ContactType::CUSTOMER;
    }

    public function customerIndex()
    {
        return Inertia::render($this->source . 'contacts/customer/index', [
            "title" => 'Customer | Jurnalin',
        ]);
    }

    public function show($id)
    {
        $data = $this->contactService->getDetail($id);
        return Inertia::render($this->source . 'contacts/customer/detail', [
            "title" => 'Customer Detail | Jurnalin',
            "additional" => [
                "data" => $data
            ]
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->contactService->getData($request, $this->typeCustomer);

            $result = new CustomerListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createData(ContactRequest $request)
    {
        try {
            $data = $this->contactService->createData($request, $this->typeCustomer);
            $result = new SubmitDefaultResource($data, 'Success create a new customer');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData(Contact $contact, UpdateContactRequest $request)
    {
        try {
            $data = $this->contactService->updateData($contact, $request, $this->typeCustomer);
            $result = new SubmitDefaultResource($data, 'Success update customer');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id, Request $request)
    {
        try {
            $data = $this->contactService->deleteData($id, $request, $this->typeCustomer);
            $result = new SubmitDefaultResource($data, 'Success delete customer');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
