<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\UserEmailAutomation;
use App\Services\CompanyService;
use App\Services\SubscriptionPlanService;
use App\Traits\CountryCodeDetailsTrait;
use DataTables;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use CountryCodeDetailsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd($request['over_sales_condition']);
            $data = $this->companyService->getData($request);
            return $this->initDataTable($data);
        } else {
            return view('company.index');
        }
    }

    /**
     * create function
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $plans = (new SubscriptionPlanService())->getData($request)->where('status', config('params.active'))->orderBy('id', 'desc')->get();
        $selectedCountryCodes = array('us');
        return view('company.create', ['plans' => $plans, 'selectedcountrycodes' => $selectedCountryCodes]);
    }
    /**
     * appendContact function
     *
     * @return Response
     */
    public function appendContact()
    {
        return view('company.newcontact');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        try {
            $this->companyService->storeCompany($request);
            return $this->success_response(200, trans('message.message.added', ['module' => trans('message.label.company')]));
        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return Response
     */
    public function show(string $uuid)
    {
        $data = $this->companyService->getCompany($uuid);
        $subscription = (new SubscriptionPlanService())->getCustomerActiveSubscription($data->id);
		if($data->customer_type==1)
			$data_= $this->companyService->getCounts(array($data->id));
		else
			$data_ = $this->companyService->getCounts($data->customers->pluck('id')); 
        return view('company.details', ['data' => $data, 'data_' => $data_, 'subscription'=>$subscription]);
    }
    /**
     * getAllContacts function
     *
     * @param string $uuid
     * @param Request $request
     * @return  \Illuminate\Http\Response
     */
    public function getAllContacts(string $uuid, Request $request)
    {
        $data = $this->companyService->getCompany($uuid);
        return $this->contactDataTable($data->contacts);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid, Request $request)
    {
        $plans = (new SubscriptionPlanService())->getData($request)->where('status', config('params.active'))->orderBy('id', 'desc')->get();
        $data = $this->companyService->getCompany($uuid);
        $selectedCountryCodes = array_column($data->contacts->toArray(), 'country_code');
        $email_period_ids = UserEmailAutomation::where('user_id', $data->id)->pluck('email_periods')->toArray();

        return view('company.edit', ['plans' => $plans, 'data' => $data, 'selectedcountrycodes' => $selectedCountryCodes, 'email_period_ids' => $email_period_ids]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyUpdateRequest $request, $uuid)
    {
        try {
            $this->companyService->updateCompany($request, $uuid);
            return $this->success_response(200, trans('message.message.updated', ['module' => trans('message.label.company')]));

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $this->companyService->deleteCompany($uuid);
        return $this->success_response(200, trans('message.message.deleted', ['module' => trans('message.label.company')]));

    }
    /**
     * changeStatus function
     *
     * @param Request $request
     * @return  \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $isUpdated = $this->companyService->updateStatus($request);
        if ($isUpdated) {
            return $this->success_response(200, trans('message.message.status_changed'));
        } else {
            return $this->error_response(400, trans('message.message.status_updated_failure', ['module' => trans('message.label.company')]));
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
            ->editColumn('name', function ($value) {
                $cls = ($value->over_sales>0) ?"text-danger":'';
                return '<span  class="'.$cls.'" data-toggle="tooltip" title="' . $value->name . '">' . mb_strimwidth($value->name, 0, 25, "...") . '</span>';
            })
            ->editColumn('email', function ($value) {
                return $value->email;
            })
            ->editColumn('customer_type', function ($value) {
                return $value->customer_type ? config('params.customer_types.' . $value->customer_type) : 'Parent Company';
            })
            ->editColumn('is_trial', function ($value) {
                return view('company.gettrialstatus', ['value' => $value]);
            })
            ->editColumn('getPlan.name', function ($value) {
                if(isset($value->getPlan->name)){
                    if (strlen($value->getPlan->name) > 25)
                    return '<span title="'.$value->getPlan->name.'">'.substr($value->getPlan->name, 0, 28) . '...'.'</span>';
                }
                return '-';
            })
            ->editColumn('over_sales_amount', function ($data) {
                if ($data->over_sales == 1) {
                    return $data->over_sales_amount;
                }
                return '-';
            })
            ->editColumn('status', function ($value) {
                return view('company.getstatus', ['value' => $value]);
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->editColumn('next_billing_date', function ($data) {
                return $data->next_billing_date != null ? \Carbon\Carbon::parse($data->next_billing_date)->format('d/m/Y h:i:s A') : '';
            })
            ->editColumn('brands', function ($data) {
                if ($data->role == 2 && $data->customer_type == 1) {
                    return '-';
                }
                return count($data->customers);
            })
            ->editColumn('stores', function ($data) {
				if($data->customer_type==1)
					return \App\Models\Store::whereIn('user_id', array($data->id))->count();
				else
					 return \App\Models\Store::whereIn('user_id', $data->customers->pluck('id'))->count();
               
            })
            ->editColumn('emails', function ($data) {
                return $data->email_count;
            })
            ->editColumn('requests', function ($data) {
				return $data->success_count;
                //return \App\Models\AmazonOrder::whereIn('user_id', $data->customers->pluck('id'))->count();
            })
            ->addColumn('action', function ($value) {
                if ($value->customer_type == config('params.individual_brand')) {
                    return view('customers.action-button', ['value' => $value]);

                }
                return view('company.action-button', ['value' => $value]);

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
    public function contactDataTable($data)
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('contact_name', function ($value) {
                return '<span data-toggle="tooltip" title="' . $value->contact_name . '">' . mb_strimwidth($value->contact_name, 0, 25, "...") . '</span>';

            })
            ->editColumn('email', function ($value) {
                return $value->email;
            })
            ->editColumn('contact_title', function ($value) {
                return $value->contact_title;
            })
            ->editColumn('contact_number', function ($value) {
                return $value->contact_number != null ? $this->countryDetails($value->country_code) . ' ' . $value->contact_number : $value->contact_number;
            })
            ->escapeColumns([])
            ->make(true);

    }

    public function fetchAllCompanies()
    {
        return $this->companyService->fetchAllCompanies();
    }

    public function fetchCustomerOfCompany(Request $request)
    {
        return $this->companyService->fetchCustomerOfCompany($request);
    }
}
