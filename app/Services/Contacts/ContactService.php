<?php

namespace App\Services\Contacts;

use App\Enum\Contacts\ContactType;
use App\Models\Contact;

class ContactService
{
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
        $contact = Contact::findOrFail($id);

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
