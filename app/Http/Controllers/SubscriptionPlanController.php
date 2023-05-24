<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionPlanAddRequest;
use App\Services\SubscriptionPlanService;
use DataTables;
use Exception;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(SubscriptionPlanService $planService)
    {
        $this->planService = $planService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->planService->getData($request);
            return $this->initDataTable($data);
        } else {
            return view('plans.index');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionPlanAddRequest $request)
    {
        try {
            $plan = $this->planService->storePlan($request);
            return $this->success_response(200, trans('message.message.added', ['module' => trans('message.label.plan')]));

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid, Request $request)
    {
        $data = $this->planService->getPlan($uuid);
        return view('plans.details', ['data' => $data]);

    }
    public function getAllCompany($uuid, Request $request)
    {
        $data = $this->planService->getPlan($uuid);
        return $this->customerDataTable($data->getCompanies);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriptionPlanAddRequest $request, $uuid)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        try {

            $isDeleted = $this->planService->deletePlan($uuid);
            if ($isDeleted) {
                return $this->success_response(200, trans('message.message.deleted', ['module' => trans('message.label.plan')]));

            } else {
                return $this->error_response(400, 'Plan can not be deleted because, this plan is associated with any client');
            }
        } catch (\Throwable$th) {
            return back()->withErrors(['message' => $th->getMessage()]);
        }
    }
    /**
     * updateStatus function
     *
     * @param Request $request
     * @return void
     */
    public function changeStatus(Request $request)
    {
        try {
            $isUpdated = $this->planService->updateStatus($request);
            if ($isUpdated) {
                return $this->success_response(200, trans('message.message.status_changed'));

            } else {
                return $this->error_response(400, trans('message.message.status_updated_failure', ['module' => trans('message.label.plan')]));
            }

        } catch (Exception $ex) {
            return $this->error_response(400, $ex->getMessage());
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
                return '<span data-toggle="tooltip" title="' . $value->name . '">' . mb_strimwidth($value->name, 0, 25, "...") . '</span>';
            })
            ->editColumn('amount', function ($value) {
                return '$' . $value->amount;
            })
            ->addColumn('company_count', function ($value) {
                return 'Parent Company - ' . $value->getParentCompany()->count() . '<br> Customers - ' . $value->getCustomer()->count();
            })
            ->editColumn('plan_type', function ($value) {
                return config('params.plan_types.' . $value->plan_type);
            })
            ->editColumn('interval', function ($value) {

                $interval_count = $value->interval_count ? $value->interval_count . '-' : '';
                return $interval_count . config('params.plan_durations.' . $value->interval) . '(s)';

            })
            ->orderColumn('interval', function ($query, $order) {
                $query->orderBy('interval_count', $order);
            })
            ->editColumn('status', function ($value) {
                $isused = $this->planService->checkPlanUsed($value->id);

                return view('plans.getstatus', ['value' => $value, 'isused' => $isused]);
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->addColumn('action', function ($value) {
                $isused = $this->planService->checkPlanUsed($value->id);
                return view('plans.action-button', ['value' => $value, 'isused' => $isused]);

            })
            ->escapeColumns([])
            ->make(true);
    }
    /**
     * initDataTable function
     *
     * @param [type] $data
     * @return object
     */
    public function customerDataTable($data)
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($value) {
                return $value->name;
            })
            ->addColumn('email', function ($value) {
                return $value->email;
            })
            ->editColumn('plan_price', function ($value) {
                return '$' . $value->plan_price;
            })
            ->editColumn('is_trial', function ($value) {
                return $value->is_trial ? ' <span class="badge rounded-pill bg-success ">Yes</span>' : '<span class="badge rounded-pill bg-warning" >No</span>';
            })
            ->editColumn('trial_days', function ($value) {
                return $value->trial_days;
            })
            ->addColumn('status', function ($value) {
                return $value->status;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->escapeColumns([])
            ->make(true);
    }
}
