<?php

namespace App\Actions\Utility\Dashboard;

use App\Actions\Utility\Contact\GetContactMenuAction;
use App\Actions\Utility\Setting\GetSystemSettingMenuAction;

class GetSidebarMenuAction
{
    public function handle()
    {
        $getSystemSettingMenu = new GetSystemSettingMenuAction();

        return [
            [
                'text' => 'Dashboard',
                'url'  => route('dashboard.index'),
                'icon' => 'VDashboard',
                'can'  => 'view_general_dashboard'
            ],
            [
                'text' => 'Audit Trails',
                'icon' => 'VEmployee',
                'group' => true,
                'can'  => ['view_admin_logs'],
                'submenu' => [
                    [
                        'text' => 'Admin Logs',
                        'url'  => route('audits.admin-logs.index'),
                        'can'  => 'view_admin_logs',
                    ],
                    [
                        'text' => 'Api Logs',
                        'url'  => route('audits.api-logs.index'),
                        'can'  => 'view_admin_logs',
                    ]
                ],
            ],
            [
                'text' => 'Transaction',
                'icon' => 'VSetting',
                'group' => true,
                // 'can' => ['view_systems_role_management'],
                'submenu' => [
                    [
                        'text' => 'Test 1',
                        'url'  => route('test'),
                        // 'can'  => ['view_systems_role_management']
                    ],
                    [
                        'text' => 'Test 2',
                        'url'  => route('test2'),
                        // 'can'  => ['view_systems_role_management']
                    ]
                ],
            ],
            [
                'text' => 'Journals',
                'icon' => 'VJournal',
                'group' => true,
                // 'can' => ['view_customer', 'view_supplier'],
                'submenu' => [
                    [
                        'text' => 'Account Category',
                        'url'  => route('journals.account-categories.index'),
                        // 'can'  => ['view_customer']
                    ],
                    [
                        'text' => 'Account',
                        'url'  => route('journals.accounts.index'),
                        // 'can'  => ['view_customer']
                    ],

                ],
            ],

            [
                'text' => 'Contacts',
                'icon' => 'VUser',
                'group' => true,
                'can' => ['view_customer', 'view_supplier'],
                'submenu' => [
                    [
                        'text' => 'Customer',
                        'url'  => route('contacts.customer.index'),
                        'can'  => ['view_customer']
                    ],
                    [
                        'text' => 'Supplier',
                        'url'  => route('contacts.supplier.index'),
                        'can'  => ['view_supplier']
                    ]
                ],
            ],
            [
                'text' => 'Settings',
                'icon' => 'VSetting',
                'group' => true,
                'can' => ['view_systems_role_management', 'view_systems_user_management'],
                'submenu' => [
                    [
                        'text' => 'Systems',
                        'url'  => $getSystemSettingMenu->handle()[1]['url'] ?? route('settings.systems.role.index'),
                        'can'  => ['view_systems_role_management', 'view_systems_user_management']
                    ]
                ],
            ]
        ];
    }
}
