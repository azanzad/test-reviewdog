<?php

namespace App\Http\Controllers;

use App\Helpers\MarketPlaceHelper;
use App\Http\Requests\StoreAddRequest;
use App\Models\Store;
use App\Models\StoreConfig;
use App\Models\User;
use App\Services\StoreService;
use DataTables;
use Exception;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->storeService->getData($request);
            return $this->initDataTable($data);
        } else {
            return view('store.index');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, StoreService $storeService)
    {

        $action = 'add';
        $store_types = StoreConfig::where(['store_marketplace' => 'Amazon'])->get();
        $amazonLoginSuccess = 0;
        $sellerDetail = [];
        if (isset($request->spapi_oauth_code)) {
            $sellerDetail['selling_partner_id'] = $request->selling_partner_id;
            $response = $storeService->getRefreshToken($request);
            $response = json_decode($response, true);
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
        $user = [];
        $viewfile = 'store.create';

        if (!(auth()->user()) && session('user')) {
            $user = User::where('uuid', session('user'))->first();
            $stores = [];
            if (!empty($user)) {
                $stores = Store::with('storeCredentials')->where(['user_id' => $user->id])->get();
            }
            $viewfile = 'customer_store.create';
        }

        return view($viewfile, ['store_types' => $store_types, 'amazonLoginSuccess' => $amazonLoginSuccess, 'configuration' => $configuration, 'sellerDetail' => $sellerDetail, 'css' => $css, 'action' => $action, 'stores' => $stores, 'user' => $user]);

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
            $storeService->addStore($request);
            return $this->success_response(200, trans('message.message.added', ['module' => trans('message.label.store')]));
        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid, StoreService $storeService)
    {
        $storeService->deleteStore($uuid);
        return $this->success_response(200, trans('message.message.deleted', ['module' => trans('message.label.store')]));

    }
    /**
     * changeStatus function
     *
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request, StoreService $storeService)
    {
        try {
            $isUpdated = $storeService->updateStatus($request);
            if ($isUpdated) {
                return $this->success_response(200, trans('message.message.status_changed'));

            } else {
                return $this->error_response(400, trans('message.message.status_updated_failure', ['module' => trans('message.label.store')]));
            }

        } catch (Exception $ex) {
            return $this->error_response(400, $ex->getMessage());
        }
    }
    /**
     * importBulkStore function
     *
     * @return Response
     */
    public function importBulkStore()
    {
        return view('store.import_customer_store');
    }
    /**
     * submitStoreExcel function
     *
     * @param Request $request
     * @param StoreService $storeService
     * @return Response
     */
    public function submitStoreExcel(Request $request, StoreService $storeService)
    {
        try {
            return $storeService->importCustomerStore($request);

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function initDataTable($data)
    {

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('store_name', function ($value) {
                return $value->store_name ? '<a  data-toggle="tooltip"   title="' . $value->store_name . '">' . mb_strimwidth($value->store_name, 0, 25, "...") . '</a>' : '';

            })
            ->editColumn('getCustomer.getCompany.name', function ($value) {
                return !empty($value->getCustomer->getCompany) ? '<a  data-toggle="tooltip"  title="' . $value->getCustomer->getCompany->name . '">' . mb_strimwidth($value->getCustomer->getCompany->name, 0, 25, "...") . '</a>' : '--';

            })
            ->editColumn('getCompany.name', function ($value) {
                return $value->getCustomer ? '<a  data-toggle="tooltip" title="' . $value->getCustomer->name . '">' . mb_strimwidth($value->getCustomer->name, 0, 25, "...") . '</a>' : '';

            })
            ->editColumn('store_type', function ($value) {
                return $value->store_type;
            })
            ->editColumn('status', function ($value) {
                return view('store.getstatus', ['value' => $value]);
            })
            ->editColumn('created_at', function ($data) {
                return date('d M Y h:i a', strtotime(ConvertTimezone($data->created_at)));
            })
            ->addColumn('action', function ($value) {
                return view('store.action-button', ['value' => $value]);

            })
            ->escapeColumns([])
            ->make(true);
    }
}
