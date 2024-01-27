<?php

namespace App\Services\Contacts;

use App\Enum\Contacts\ContactType;
use App\Helpers\EncryptOrthogonal;
use App\Models\Contact;


class ContactService
{
    private $encryptService;
    public function __construct()
    {
        $this->encryptService = new EncryptOrthogonal();
    }
    public function getData($request, $type = ContactType::CUSTOMER)
    {
        $search = $request->search;

        $query = Contact::where('type', $type);

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });

        return $query->paginate(10);
    }

    public function getDetail($id)
    {
        $contact = Contact::with([
            'sales',
            'purchases',
        ])->findOrFail($id);


        // get history transactions of contact

        return $contact;
    }

    public function createData($request, $type)
    {
        $data = $request->only([
            'name',
            'description',
            'email',
            'phone_number',
            'address',
            'city',
            'portal_code',
        ]);

        $fieldsToEncrypt = [
            'name',
            'description',
            'email',
            'phone_number',
            'address',
            'city',
            'portal_code',
        ];
        $step_size = 4;

        foreach ($fieldsToEncrypt as $field) {
            $data[$field] = $this->encryptService->orthogonal_encrypt($data[$field], $step_size);
        }

        $data['type'] = $type;

        $contact = Contact::create($data);

        return $contact;
    }

    public function updateData($contact, $request, $type)
    {
        // dd($contact, $request, $type);

        $data = $request->only([
            'name',
            'description',
            'email',
            'phone_number',
            'address',
            'city',
            'portal_code',
        ]);

        $data['type'] = $type;

        $contact = Contact::findOrFail($contact->id);
        $contact->update($data);

        return $contact;
    }

    public function deleteData($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return $contact;
    }
}
