<?php
namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\CustomerCard;
use Illuminate\Http\Request;
use App\Models\CompanyContact;
use App\Models\SubscriptionPlan;
use Laravel\Cashier\Subscription;
use App\Jobs\CompanyRegisterQueue;
use App\Traits\GetStripeClientTrait;
use Illuminate\Support\Facades\Hash;

class CompanyService
{
    use GetStripeClientTrait;

    /**
     * getData function
     * get all companys from users table
     * @param Request $request
     * @return object
     */
    public function getData(Request $request)
    {

        return User::with('getPlan', 'customers')->select('users.*')
            ->where('role', config('params.company_role'))
            ->when($request['status'], function ($query) use ($request) {
                $query->where('status', $request['status']);
            })
            ->when($request['company_name'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('name', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['company_email'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('email', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['customer_type'], function ($query, $request) {
                $request == 2 ? $request = null : '';
                $query->where('customer_type', $request);
            })
            ->when(isset($request['is_trial']), function ($query) use ($request) {
                $query->where('is_trial', $request['is_trial']);
            })
            ->when($request['trial_condition'] && $request['trial_condition'] != 'Range', function ($query) use ($request) {
                $query->where('trial_days', $request['trial_condition'], $request['range_from']);
            })
            ->when($request['trial_condition'] && $request['trial_condition'] == 'Range', function ($query) use ($request) {
                $query->whereBetween('trial_days', [$request['range_from'], $request['range_to']]);
            })
            ->when($request['plan'], function ($query, $request) {
                $query->whereHas('getPlan', function ($query) use ($request) {
                    $terms = explode(',', $request);
                    $query->where(function ($query) use ($terms) {
                        foreach ($terms as $term) {
                            $query->orWhere('name', 'like', "%" . trim($term) . "%");
                        };
                    });
                });
            })
            ->when($request['date_range'], function ($query, $request) {
                $dates = explode('to', $request);
                $query->when(count($dates) == 1, function ($query) use ($dates) {
                    $query->whereDate('created_at', '=', $dates[0]);
                })
                    ->when(count($dates) == 2, function ($query) use ($dates) {
                        $query->whereBetween('created_at', [$dates[0], $dates[1]]);
                    });
            })

            ->when($request['over_sales_condition'] && $request['over_sales_condition'] != 'Range', function ($query) use ($request) {

                $query->where('over_sales_amount', $request['over_sales_condition'], $request['over_sales_from']);
            })
            ->when($request['over_sales_condition'] && $request['over_sales_condition'] == 'Range', function ($query) use ($request) {
                $query->whereBetween('over_sales_amount', [$request['over_sales_from'], $request['over_sales_to']]);
            })

            ->when($request['over_sales'], function ($query, $request) {
                $query->where('over_sales', $request);
            });

    }
    /**
     * getCompany function
     *
     * @param string $uuid
     * @return object
     */
    public function getCompany(string $uuid)
    {
        $user = User::with('getPlan', 'contacts', 'getEmailAutomations', 'customers')->where('uuid', $uuid)->first();
        return $user;
    }
    public function getCounts($data){
        $counts_['stores'] = \App\Models\Store::whereIn('user_id',$data)->count();
        $counts_['requests'] = \App\Models\AmazonOrder::whereIn('user_id',$data)->count();
        return $counts_;
    }
    /**
     * storeClient function
     *
     * @param Request $request
     * @return object
     */
    public function storeCompany(Request $request)
    {
        $stripe_customerid = '';
        //sub customer are not add on stripe
        if (empty($request->customer_type) || $request->customer_type != config('params.parent_company')) {
            $stripe_customerid = $this->saveCustomerOnStripe($request);
        }
        return $this->saveCompanyOrCustomer($request, $stripe_customerid);
    }
    /**
     * saveCompanyOrCustomer function
     *
     * @param Request $request
     * @param string|null $stripe_customerid
     * @return object
     */
    public function saveCompanyOrCustomer(Request $request, string $stripe_customerid = null)
    {
        $company = new User;
        $company->uuid = Str::uuid();
        $company->email_verified_at = date('Y-m-d H:i:s');
        //set password if only to parent company & individual customer
        $isCompany = false;
        $role = config('params.user_roles.customer');
        $plan = [];
        if (empty($request->customer_type) || $request->customer_type == config('params.individual_brand')) {
            $isCompany = true;
            $role = config('params.user_roles.company');
            $password = Str::random(8);
            $company->password = Hash::make($password);
            $plan = (new SubscriptionPlanService())->getPlan($request->planid);
        }
        $company->companyid = $request->companyid ?? null;
        $company->role = $role;
        $company->planid = $plan ? $plan->id : null;
        $company->name = $request->name ?? $request->clientname;
        $company->email = $request->email;
        $company->company_description = $request->company_description ?? '';
        $company->website = $request->website ?? null;
        $company->is_trial = is_string($request->is_trial) ?? 0;
        $company->trial_days = $request->trial_days ?? null;
        $company->plan_price = $plan ? $plan->amount : null;
        $company->status = $request->status;
        $company->customer_type = $request->customer_type ?? null;
        $company->created_by = auth()->user()->id;
        $company->stripe_id = $stripe_customerid;
        $company->save();
        //send password mail to only parent company & individual customer
        if ($isCompany) {
            $emailData['name'] = $company->name ?? $request->clientname;
            $emailData['email'] = $company->email;
            $emailData['password'] = $password;
            $registrationEmailQueue = new CompanyRegisterQueue($emailData);
            dispatch($registrationEmailQueue)->delay(now()->addSeconds(6));
        }
        $this->addOrUpdateCompanyContact($request, $company->id);
        //email update period
        (new SettingService())->addOrUpdateCompanyEmailPeriod($request, $company->id);

        return $company;
    }
    /**
     * addOrUpdateCompanyContact function
     *
     * @param Request $request
     * @param integer $companyid
     * @param string $company_uuid
     * @return void
     */
    public function addOrUpdateCompanyContact(Request $request, int $companyid, string $company_uuid = '')
    {
        $new_valueids = [];
        if (!empty($request->contact)) {
            foreach ($request->contact as $key => $value) {
                if (!empty($value['contact_name'])) {
                    $company_contact = new CompanyContact;
                    if (!empty($value['contactid'])) {
                        $company_contact = CompanyContact::find($value['contactid']);
                    }
                    $company_contact->uuid = Str::uuid();
                    $company_contact->companyid = $companyid;
                    $company_contact->contact_name = $value['contact_name'];
                    $company_contact->contact_title = $value['contact_title'];
                    $company_contact->email = $value['contact_email'];
                    $company_contact->country_code = $value['contact_number'] == null ? null : $value['country_code'];
                    $company_contact->contact_number = $value['contact_number'];
                    $company_contact->save();
                    array_push($new_valueids, $company_contact->id);
                }
            }
            //delete old contacts at update time
            if (!empty($companyid)) {
                User::deleteContacts($companyid, $new_valueids);
            }
        }
    }
    /**
     * updateCompany function
     *
     * @param Request $request
     * @param string $uuid
     * @return object
     */
    public function updateCompany(Request $request, string $uuid)
    {

        $customer = $this->getCompany($uuid);
        $role = config('params.user_roles.customer');
        $plan = [];
        if (empty($request->customer_type) || $request->customer_type != config('params.parent_company')) {
            $role = config('params.user_roles.company');
            if (!empty($request->planid)) {

                $plan = (new SubscriptionPlanService())->getPlan($request->planid);

            }
        }

        //update customer on stripe
        if ($role == config('params.user_roles.company')) {
            $this->updateCustomerOnStripe($request, $customer->stripe_id);
        }
        $company = $this->getCompany($uuid);
        //$company->companyid = $request->companyid ?? null;
        $company->planid = $plan ? $plan->id : null;
        $company->plan_price = $plan ? $plan->amount : null;
        $company->role = $role;
        $company->name = $request->name ?? null;
        //$company->email = $request->email;
        $company->company_description = $request->company_description ?? '';
        $company->website = $request->website;
        $company->is_trial = is_string($request->is_trial) ?? 0;
        $company->trial_days = $request->trial_days ?? null;
        $company->status = $request->status;
        //$company->customer_type = $request->customer_type;
        $company->save();

        $this->addOrUpdateCompanyContact($request, $company->id);
        //email update period
        (new SettingService())->addOrUpdateCompanyEmailPeriod($request, $company->id);
        return true;
    }
    /**
     * deleteCompany function
     *
     * @param string $uuid
     * @return object
     */
    public function deleteCompany(string $uuid)
    {
        $user = $this->getCompany($uuid);
        if ($user->stripe_id) {
            $this->deleteCustomerOnStripe($user->stripe_id);
        }
        //delete company contacts & customers
        CompanyContact::where('companyid', $user->id)->delete();
        User::where('companyid', $user->id)->delete();
        $user->update([
            'email' => time() . '::' . $user->email,
            'status' => 3,
            'deleted_by' => Auth()->user()->id,
        ]);
        return $user->delete();
    }
    /**
     * updateStatus function
     *
     * @param Request $request
     * @return boolean
     */
    public function updateStatus(Request $request)
    {
        $user = $this->getCompany($request->uuid);
        return $user->update(['status' => $request->status, 'updated_by' => Auth()->user()->id]);

    }
    /**
     * checkPlanUsed function
     *
     * @param integer $id
     * @return object
     */
    public function checkCompanyUsed(int $id)
    {
        return User::where('companyid', $id)->first();
    }
    /** save customer on stripe account function */
    public function saveCustomerOnStripe(Request $request)
    {
        $stripe = $this->getStripeClient();

        try {
            //this plan module is stripe plan
            $customer = $stripe->customers->create([
                'name' => $request->name,
                'email' => $request->email,
                'description' => $request->company_description ?? '',
            ]);
            return $customer->id;
        } catch (Exception $e) {
            throw new Exception('Error on creating subscription plan. ' . $e->getMessage());
        }
    }
    /** update customer on stripe account function */
    public function updateCustomerOnStripe(Request $request, string $stripe_id)
    {
        $stripe = $this->getStripeClient();

        try {
            $customer = $stripe->customers->update($stripe_id,
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'description' => $request->company_description ?? '',
                ]);

            $plan = SubscriptionPlan::where('uuid', $request->planid)->first();
            $user = User::where('uuid',$request->uuid)->first();

            if(!empty($plan) &&  $user->planid != $plan->id && isset($customer->subscriptions->data[0]->id)  && isset($customer->subscriptions->data[0]->items['data'][0]->id) && !empty($customer->subscriptions->data[0]->id)){
                User::where('uuid',$request->uuid)->update(['over_sales'=>0]);
                $data['items'] = [
                                    [
                                    'id' => $customer->subscriptions->data[0]->items['data'][0]->id,
                                    'price' => $plan->stripe_priceid,
                                    ],
                                ];

                /*
                if(isset($user->promo_code_id) && !empty($user->promo_code_id) ){

                    // $data['promotion_code'] = $user->promo_code_id;

                    // $coupon = $user->findActivePromotionCode($user->promo_code);
                    // $user->discount_price = 0;
                    // $user->save();
                }
                */
                $upd = $stripe->subscriptions->update(
                    $customer->subscriptions->data[0]->id,
                    [
                        $data
                    ]
                );
            }

            return $customer->id;
        } catch (Exception $e) {
            throw new Exception('Error on updating customer. ' . $e->getMessage());
        }
    }
    /**delete customer on stripe account function */
    public function deleteCustomerOnStripe(string $stripe_customerid)
    {
        $stripe = $this->getStripeClient();
        try {
            //this plan module is stripe plan
            return $stripe->customers->delete($stripe_customerid, []);
        } catch (Exception $e) {
            throw new Exception('Error on creating subscription plan. ' . $e->getMessage());
        }
    }
    /**
     * fetchAllCompanies function
     *
     * @return object
     */
    public function fetchAllCompanies()
    {
        return User::where('role', config('params.company_role'))->get();
    }
    /**
     * fetchCustomerOfCompany function
     *
     * @param Request $request
     * @return object
     */
    public function fetchCustomerOfCompany(Request $request)
    {
		
        return User::whereHas('stores')->where('companyid', $request->companyId)->get();
    }

    /**
     * signupUser function : Signup individual customer type only
     * @param Request $request
     * @return object
     */
    public function signupUser(Request $request){

        try{
            //create user in strip
            $stripe_customerid = $this->saveCustomerOnStripe($request);

            if(!empty($stripe_customerid)){

                //create user in database
                $user = $this->insertSignupUser($request, $stripe_customerid);

                $this->addUserContact($request, $user);

                return $user;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    /**
     * insertSignupUser function
     *
     * @param Request $request
     * @param string $stripe_customerid
     * @return object
     */
    public function insertSignupUser(Request $request, string $stripe_customerid){

        $plan = (new SubscriptionPlanService())->getPlan($request->planid);

        $timezone = (new SettingService())->getTimezoneByCountryCode($request->country_id);

        $user = new user();
        $user->uuid      = Str::uuid();
        $user->role      = config('params.user_roles.company');
        $user->planid    = $plan ? $plan->id : null;
        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->password     = Hash::make($request->password);

        if($plan->trial_days > 0){
            $user->is_trial  = config('params.is_trial');
            $user->trial_days= $plan->trial_days;
        }

        $user->plan_price= $plan ? $plan->amount : null;
        $user->status    = config('params.active');
        $user->customer_type = config('params.individual_brand');
        $user->stripe_id = $stripe_customerid;
        $user->country_id = $request->country_id;
        $user->timezone = $timezone;
        $user->save();
        return $user;
    }

    /**
     * addUserContact function
     * @param Request $request
     * @param object $user
     * @return void
     */
    public function addUserContact(Request $request, object $user){

        CompanyContact::create([
            'companyid' => $user->id,
            'uuid'      => Str::uuid(),
            'contact_name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'country_code' => $request->country_code,
            'status'    => config('params.active')
        ]);
    }
}
