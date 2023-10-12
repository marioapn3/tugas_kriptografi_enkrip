<?php

namespace App\Http\Controllers\Transactions\Selling;

use App\Actions\Options\GetAccountOptions;
use App\Actions\Options\GetCustomerOptions;
use App\Enum\Accounts\Classification;
use App\Http\Controllers\AdminBaseController;
use App\Http\Requests\Transactions\Selling\SaveSettingPosRequest;
use App\Http\Resources\SubmitDefaultResource;
use App\Http\Resources\Transactions\Selling\ProductPosListResource;
use App\Models\CartSetting;
use App\Services\Storages\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends AdminBaseController
{

    public function __construct(GetAccountOptions $getAccountOptions, GetCustomerOptions $getCustomerOptions)
    {
        $this->accountOptions = $getAccountOptions;
        $this->customerOptions = $getCustomerOptions;
    }

    public function posIndex()
    {

        $cart_setting = CartSetting::first();

        // date now 
        $date = $cart_setting->date_default ?? date('Y-m-d');

        // append in $cart_setting
        $cart_setting->date = $date;

        return Inertia::render($this->source . 'transactions/selling/pos', [
            'title' => 'POS | Jurnalin',
            'additionals' => [
                'account_list' => $this->accountOptions->handle([Classification::HARTA]),
                'customer_list' =>  $this->customerOptions->handle(),
                'cart_setting' => $cart_setting,
            ],
        ]);
    }

    public function getProduct(Request $request)
    {
        $product = new ProductService();

        try {
            $data = $product->getDataPos($request);
            $result = new ProductPosListResource($data, 'Success get product list');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function saveSetting(SaveSettingPosRequest $request)
    {
        try {
            $inputs = $request->only(['deposit_to_account_id', 'date']);

            $cart_setting = CartSetting::first();

            if ($cart_setting) {
                $cart_setting->update($inputs);
            } else {
                CartSetting::create($inputs);
            }

            $result = new SubmitDefaultResource(null, 'Success save setting');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        try {
            $inputs = $request->only(['deposit_to_account_id', 'date', 'customer_id', 'product_id']);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
