<?php

namespace App\Actions\Utility\Dashboard;

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
                'text' => 'Report',
                'group' => true,
                'icon' => 'VJournal',
                'submenu' => [
                    [
                        'text' => 'General Ledger Report',
                        'url'  => route('report.ledger.index'),
                        // 'can'  => 'view_admin_logs',
                    ],
                    [
                        'text' => 'Trial Balance Report',
                        'url'  => route('report.balance.index'),
                        // 'can'  => 'view_admin_logs',
                    ]
                ],
                // 'can'  => 'view_general_dashboard'
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
                        'text' => 'POS',
                        'url'  => route('transaction.pos.index'),
                        // 'can'  => ['view_systems_role_management']
                    ],
                    [
                        'text' => 'Sales',
                        'url'  => route('transaction.sale.index'),
                        // 'can'  => ['view_systems_role_management']
                    ],
                    [
                        'text' => 'Purchase',
                        'url'  => route('transaction.purchase.index'),
                        // 'can'  => ['view_systems_role_management']
                    ],
                    [
                        'text' => 'Expense',
                        'url'  => route('transaction.expense.index'),
                        // 'can'  => ['view_systems_role_management']
                    ]
                ],
            ],
            [
                'text' => 'Accounting',
                'icon' => 'VJournal',
                'group' => true,
                'can' => ['view_account', 'view_account_category', 'view_manual_journal'],
                'submenu' => [
                    [
                        'text' => 'Journal',
                        'url'  => route('journals.journal.index'),
                        'can'  => ['view_manual_journal']
                    ],
                    [
                        'text' => 'Account',
                        'url'  => route('journals.accounts.index'),
                        'can'  => ['view_account']
                    ],
                    [
                        'text' => 'Account Category',
                        'url'  => route('journals.account-categories.index'),
                        'can'  => ['view_account_category']
                    ],

                ],
            ],
            [
                'text' => 'Product',
                'url'  => route('storages.product.index'),
                'icon' => 'VProduct',
                'can'  => 'view_storage_product'
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
