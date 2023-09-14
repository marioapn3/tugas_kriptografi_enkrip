<?php

namespace App\Enum\Contacts;

enum ContactType: string
{
    case customer = 'customer';
    case supplier = 'supplier';
}
