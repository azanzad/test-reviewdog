<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PaymentService;
use App\Services\SubscriptionPlanService;
use DataTables;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PaymentService $paymentService)
    {
        if ($request->ajax()) {
            $data = $paymentService->getData($request);
            return $this->initDataTable($data);
        } else {
            return view('payment.index');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SubscriptionPlanService $subscriptionPlanService)
    {
        try {
            if ($request->subscription_type == 'active') {
                $plan = $subscriptionPlanService->activePlanSubscription($request);
                $msg = trans('message.message.activated', ['module' => trans('message.label.subscription')]);
            } else {
                $plan = $subscriptionPlanService->cancelSubscriptionPlan($request);
                $msg = trans('message.message.cancelled', ['module' => trans('message.label.subscription')]);

            }

            return $this->success_response(200, $msg);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param cancelSubscriptionByAdmin $subscriptionPlanService
     * @return void
     */
    public function cancelSubscriptionByAdmin(Request $request, SubscriptionPlanService $subscriptionPlanService)
    {
        try {

            $subscriptionPlanService->cancelSubscriptionByAdmin($request);
            $msg = trans('message.message.cancelled', ['module' => trans('message.label.subscription')]);

            return $this->success_response(200, $msg);

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }


    /**
     * initDataTable function
     *
     * @param [type] $data
     * @return \Illuminate\Http\Response
     */
    public function initDataTable($data)
    {

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($value) {
                return '<span  data-toggle="tooltip"  title="' . $value->name . '">' . mb_strimwidth($value->name, 0, 25, "...") . '</span>';
            })
            ->editColumn('company_name', function ($value) {
                $user = User::find($value->user_id);
                return $user->name;
            })
            ->editColumn('stripe_price', function ($value) {
                $user = User::find($value->user_id);
                return '$' . $user->getPlan->amount ?? '';
            })
            ->editColumn('stripe_status', function ($value) {
                return view('payment.getstatus', ['value' => $value]);
            })
            ->editColumn('trial_ends_at', function ($data) {
                return $data->trial_ends_at ? $data->trial_ends_at->format('d/m/Y h:i:s A') : '';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->escapeColumns([])
            ->make(true);
    }
}
