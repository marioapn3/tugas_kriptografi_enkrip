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
}
