<?php

namespace App\Http\Controllers\Settings\System;

use Illuminate\Http\Request;
use App\Actions\Options\GetRoleOptions;
use App\Http\Controllers\AdminBaseController;
use App\Services\Settings\User\UserManagementService;
use App\Http\Requests\Settings\User\CreateUserRequest;
use App\Http\Requests\Settings\User\UpdateUserRequest;
use App\Http\Resources\Settings\User\UserListResource;
use App\Http\Resources\Settings\User\SubmitUserResource;

class UserManagementController extends AdminBaseController
{
    public function __construct(UserManagementService $userManagementService, GetRoleOptions $getRoleOptions)
    {
        $this->userManagementService = $userManagementService;
        $this->getRoleOptions = $getRoleOptions;
    }


    public function getData(Request $request)
    {
        try {
            $data = $this->userManagementService->getData($request);

            $result = new UserListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createData(CreateUserRequest $request)
    {
        try {
            $data = $this->userManagementService->createData($request);

            $result = new SubmitUserResource($data, 'Success Create User');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function updateData($id, UpdateUserRequest $request)
    {
        try {
            $data = $this->userManagementService->updateData($id, $request);

            $result = new SubmitUserResource($data, 'Success Update User');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteData($id)
    {
        try {
            $data = $this->userManagementService->deleteData($id);

            $result = new SubmitUserResource($data, 'Success Delete User');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
