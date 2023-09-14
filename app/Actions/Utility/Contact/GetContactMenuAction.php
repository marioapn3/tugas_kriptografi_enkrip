<?php

namespace App\Actions\Utility\Contact;

use App\Helpers\Menu\Builder;
use App\Helpers\Menu\ModuleAccess;

class GetContactMenuAction
{
    public function handle()
    {
        $menu = [
            [
                'text' => 'Contacts',
                'url' => route('contacts.customer.index'),
                'header' => true
            ],
            [
                'text' => 'Customer',
                'url' => route('contacts.customer.index'),
                'icon' =>  'VRole',
                'can' => 'view_systems_role_management'
            ]
        ];

        $builderSidebar = new Builder([
            new ModuleAccess(),
        ]);

        return array_values($builderSidebar->transformItems($menu));
    }
}
