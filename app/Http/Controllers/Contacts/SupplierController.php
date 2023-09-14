<?php

namespace App\Http\Controllers\Contacts;

use App\Actions\Utility\Contact\GetContactMenuAction;
use App\Enum\Contacts\ContactType;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Contacts\ContactRequest;
use App\Http\Requests\Contacts\UpdateContactRequest;
use App\Http\Resources\Contacts\Supplier\SupplierListResource;
use App\Http\Resources\SubmitDefaultResource;
use App\Models\Contact;
use App\Services\Contacts\ContactService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends AdminBaseController
{
    // This not required for defining
    private $contactService;
    private $typeSupplier;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;

        $this->typeSupplier = ContactType::SUPPLIER;
    }

    public function supplierIndex()
    {
        return Inertia::render($this->source . 'contacts/supplier/index', [
            "title" => 'Supplier | Jurnalin',
        ]);
    }

    public function show($id)
    {
        $data = $this->contactService->getDetail($id);
        return Inertia::render($this->source . 'contacts/supplier/detail', [
            "title" => 'Supplier Detail | Jurnalin',
            "additional" => [
                "data" => $data
            ]
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->contactService->getData($request, $this->typeSupplier);

            $result = new SupplierListResource($data);

            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createData(ContactRequest $request)
    {
        try {
            $data = $this->contactService->createData($request, $this->typeSupplier);
            $result = new SubmitDefaultResource($data, 'Success create a new supplier');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData(Contact $contact, UpdateContactRequest $request)
    {
        try {
            $data = $this->contactService->updateData($contact, $request, $this->typeSupplier);
            $result = new SubmitDefaultResource($data, 'Success update supplier');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id, Request $request)
    {
        try {
            $data = $this->contactService->deleteData($id, $request, $this->typeSupplier);
            $result = new SubmitDefaultResource($data, 'Success delete supplier');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
