<?php

namespace App\Enum\Accounts;

enum Classification: string
{
    case HARTA = 'Harta';
    case KEWAJIBAN = 'Kewajiban';
    case MODAL = 'Modal';
    case PENDAPATAN = 'Pendapatan';
    case BEBAN = 'Beban';
}
