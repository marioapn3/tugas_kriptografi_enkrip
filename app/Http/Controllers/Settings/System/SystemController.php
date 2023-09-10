<?php

namespace App\Http\Controllers\Settings\System;

use App\Actions\Options\GetRoleOptions;
use Inertia\Inertia;
use App\Http\Controllers\AdminBaseController;
use App\Actions\Utility\Setting\GetSystemSettingMenuAction;

class SystemController extends AdminBaseController
{
    public function __construct(
        GetSystemSettingMenuAction $getSystemSettingMenu,
        GetRoleOptions $getRoleOptions
    ) {
        $this->getSystemSettingMenu = $getSystemSettingMenu;
        $this->getRoleOptions = $getRoleOptions;
    }

    public function roleSettingIndex()
    {
        return Inertia::render($this->source . 'settings/systems/role/Index', [
            "title" => 'Bookstore | Setting System Authentication',
            "additional" => [
                'menu' => $this->getSystemSettingMenu->handle()
            ]
        ]);
    }

    public function userSettingIndex()
    {
        return Inertia::render($this->source . 'settings/systems/user/index', [
            "title" => 'Bookstore | User managements',
            "additional" => [
                'role_list' => $this->getRoleOptions->handle(),
                'menu' => $this->getSystemSettingMenu->handle()
            ]
        ]);
    }
}
