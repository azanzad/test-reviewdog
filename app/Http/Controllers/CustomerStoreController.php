<?php

namespace App\Http\Controllers;

use App\Helpers\MarketPlaceHelper;
use App\Http\Requests\StoreAddRequest;
use App\Models\Store;
use App\Models\StoreConfig;
use App\Models\User;
use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerStoreController extends Controller
{
    public function create(Request $request, StoreService $storeService)
    {
        //get customer all stores
        $company_uuid = request()->segment(3) ?? '';

        $action = 'add';
        $store_types = StoreConfig::where(['store_marketplace' => 'Amazon'])->get();
        $amazonLoginSuccess = 0;
        $sellerDetail = [];
        session(['user' => $company_uuid]);
        $user = User::where('uuid', session('user'))->first();

        if (isset($request->spapi_oauth_code)) {
            $sellerDetail['selling_partner_id'] = $request->selling_partner_id;
            $response = $storeService->getRefreshToken($request);
            $response = json_decode($response, true);
            // Session::put('users', $company_uuid);
            //session(['user' => $company_uuid]);

            if (isset($response['error'])) {
                $sellerDetail['amazon_error'] = "Error while integrating your amazon seler account. Please try again.";
            } else {
                $amazonLoginSuccess = 1;
                $sellerDetail['refresh_token'] = $response['refresh_token'];
                $sellerDetail['access_token'] = $response['access_token'];
            }
        }

        $configuration = MarketPlaceHelper::configuration();
        $css = "market-steps";

        $stores = [];
        if (!empty($user)) {
            $stores = Store::with('storeCredentials')->where(['user_id' => $user->id])->get();
        }

        return view('customer_store.create', ['store_types' => $store_types, 'amazonLoginSuccess' => $amazonLoginSuccess, 'configuration' => $configuration, 'sellerDetail' => $sellerDetail, 'css' => $css, 'action' => $action, 'stores' => $stores, 'user' => $user]);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddRequest $request, StoreService $storeService)
    {
        try {
            $isAdded = $storeService->addStore($request);
            if ($isAdded['status']) {
                return $this->success_response(200, trans('message.message.added', ['module' => trans('message.label.store')]));
            } else {
                return $this->error_response(400, $isAdded['msg']);

            }

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }

    }
}