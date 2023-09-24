<?php

namespace App\Actions\Options;

use App\Models\Contact;

class GetCustomerOptions
{
    public function handle()
    {
        $contact = Contact::where('type', 'customer')->pluck('name', 'id');

        return $contact;
    }
}
