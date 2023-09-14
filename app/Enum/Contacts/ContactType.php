<?php

namespace App\Enum\Contacts;

enum ContactType: string
{
    case CUSTOMER = 'customer';
    case SUPPLIER = 'supplier';
}
