<?php
namespace App\Services;

use Exception;
use Stripe\Plan;
use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\User;
use Stripe\Subscription;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use App\Mail\CancelSubscriptionMail;
use App\Traits\GetStripeClientTrait;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Subscription as CashierSubscription;

class SubscriptionPlanService
{
    use GetStripeClientTrait;

    /**
     * getData function
     * get all plans
     * @param Request $request
     * @return object
     */
    public function getData(Request $request)
    {
        return SubscriptionPlan::with('getCompanies', 'getParentCompany', 'getCustomer')->select('subcription_plans.*')
            ->when($request['plan_type'], function ($query, $request) {
                $query->where('plan_type', $request);
            })
            ->when($request['plan_name'], function ($query, $request) {
                $terms = explode(',', $request);
                $query->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('name', 'like', "%" . trim($term) . "%");
                    };
                });
            })
            ->when($request['price_operation'] && $request['price_operation'] != 'Range', function ($query) use ($request) {
                $query->where('amount', $request['price_operation'], $request['price']);
            })
            ->when($request['price_operation'] && $request['price_operation'] == 'Range', function ($query) use ($request) {
                $query->whereBetween('amount', [$request['price'], $request['price_to']]);
            })
            ->when($request['interval_count'], function ($query) use ($request) {
                $query->where('interval_count', $request->interval_count)->where('interval', $request->plan_durations);
            })
            ->when($request['status'], function ($query, $request) {
                $query->where('status', $request);
            })
            ->when($request['plan_date_range'], function ($query, $request) {
                $dates = explode('to', $request);
                $query->when(count($dates) == 1, function ($query) use ($dates) {
                    $query->whereDate('created_at', '=', $dates[0]);
                })
                    ->when(count($dates) == 2, function ($query) use ($dates) {
                        $query->whereBetween('created_at', [$dates[0], $dates[1]]);
                    });
            });
    }
    /**
     * storePlan function
     * store plan on stripe & our DB
     * @param Request $request
     * @return object
     */
    public function storePlan(Request $request)
    {

        //add plan to on stripe account, if added success on stripe then add in our db
        $stripe_plan = $this->savePlanOnStripe($request);
        if ($stripe_plan) {
            $plan = new SubscriptionPlan;
            $plan->uuid = Str::uuid();
            $plan->userid = auth()->user()->id;
            $plan->stripe_planid = $stripe_plan['planid'];
            $plan->stripe_priceid = $stripe_plan['priceid'];
            $plan->trial_days = $request->trial_days;
            $plan->annual_sales_from = $request->annual_sales_from;
            $plan->annual_sales_to = $request->annual_sales_to;
            $plan->fill($request->validated());
            return $plan->save();
        }
    }

    /**
     * getPlan function
     * get plan of given id
     * @param string $uuid
     * @return object
     */
    public function getPlan(string $uuid)
    {
        return SubscriptionPlan::with('getCompanies')->where('uuid', $uuid)->first();
    }

    /**
     * getPlan getPlanById
     * get plan of given id
     * @param int $id
     * @return object
     */
    public function getPlanById(int $id)
    {
        return SubscriptionPlan::with('getCompanies')->where('id', $id)->first();
    }

    /**
     * changePlanStatus function
     * change status of plan in stripe & our DB
     * @param Request $request
     * @return boolean
     */
    public function updateStatus(Request $request)
    {
        $plan = $this->getPlan($request->uuid);
        $isUsed = $this->checkPlanUsed($plan->id);
        if ($isUsed) {
            return false;
        }
        if ($plan->stripe_planid) {
            $this->updatePlanStatusOnStripe($request, $plan->stripe_planid);
        }
        return $plan->update(['status' => $request->status, 'updated_by' => Auth()->user()->id]);

    }
    /**
     * deletePlan function
     * delete plan from stripe & our DB
     * @param string $uuid
     * @return boolean
     */
    public function deletePlan(string $uuid)
    {
        $plan = $this->getPlan($uuid);
        $isUsed = $this->checkPlanUsed($plan->id);
        if (!empty($isUsed)) {
            return false;
        }
        if ($plan->stripe_planid) {
            $this->deletePlanOnStripe($plan->stripe_planid);
        }
        SubscriptionPlan::where('uuid', $uuid)->delete();
        return true;

    }
    /**
     * checkPlanUsed function
     * check given plan is used by any customer
     * @param integer $id
     * @return object
     */
    public function checkPlanUsed(int $id)
    {
        return User::where('planid', $id)->first();
    }
    /** save plan on stripe account function */
    public function savePlanOnStripe(Request $request)
    {
        $stripe = $this->getStripeClient();
        $interval = config('params.plan_durations.' . $request->interval);
        try {
            //add product
            $product = $stripe->products->create([
                'name' => $request->name,
            ]);

            //this plan module is stripe plan
            $plan = $stripe->plans->create([
                'amount' => ($request->amount * 100),
                'currency' => 'usd',
                'interval' => $interval,
                'interval_count' => $request->interval_count ?? 1,
                'active' => $request->status == 1 ? true : false,
                'product' => $product->id,
            ]);
            $price = $stripe->prices->create([
                'unit_amount' => ($request->amount * 100),
                'currency' => 'usd',
                'recurring' => ['interval' => $interval],
                'product' => $product->id,
            ]);
            return array('planid' => $plan->id, 'priceid' => $price->id);
            return $plan->id;
        } catch (Exception $e) {
            throw new Exception('Error on creating subscription plan. ' . $e->getMessage());
        }

    }
    /** update plan status on stripe */
    public function updatePlanStatusOnStripe(Request $request, $stripe_planid)
    {
        $stripe = $this->getStripeClient();
        $interval = config('params.plan_durations.' . $request->interval);
        try {
            $plan = $stripe->plans->update($stripe_planid, [
                'active' => $request->status == 1 ? true : false,
            ]);
            return $plan->id;
        } catch (Exception $e) {
            throw new Exception('Error on change status of subscription plan. ' . $e->getMessage());
        }
    }
    /**delete plan from stripe */
    public function deletePlanOnStripe(string $planid)
    {
        $stripe = $this->getStripeClient();
        try {
            return $stripe->plans->delete($planid, []);
        } catch (Exception $e) {
            throw new Exception('Error on deleting subscription plan. ' . $e->getMessage());
        }
    }
    /**active subscription plan */
    public function activePlanSubscription(Request $request)
    {
        //active subscription, if added success on stripe then add in our db
        $subscription = $this->activePlanSubscriptionOnStripe($request);
        if (!empty($subscription)) {
            $user = User::find(auth()->user()->id);
            $user->subscription_id = $subscription->id;
            $user->subscription_item_id = $subscription->items[0] ? $subscription->items[0]['id'] : '';
            $user->is_plan_active = 1;
            $user->trial_ends_at = $subscription->trial_ends_at;
            $user->currency = $subscription->currency;
            $user->activated_date = $subscription->start_date;
            $user->save();
        }
    }
    /**active subscription plan on stripe */
    public function activePlanSubscriptionOnStripe($request)
    {
        $user = User::find(auth()->user()->id);
        $plan = SubscriptionPlan::find(auth()->user()->planid);
        $trial_days = '';

        try {
            $card = (new CompanyCardService())->getCustomerDefaultCard(auth()->user()->id);
            $stripe_cardid = !empty($card) ? $card->stripe_cardid : '';
            if ($user->is_trial) {
                $trial_days = $user->trial_days;
                $subscription = $user->newSubscription(
                    $plan->name, $plan->stripe_priceid
                )
                    ->trialDays($trial_days)
                    ->create($stripe_cardid);

            } else {
                $subscription = $user->newSubscription(
                    $plan->name, $plan->stripe_priceid
                )
                    ->create($stripe_cardid);

            }
            return $subscription;
        } catch (Exception $e) {
            throw new Exception('Error on creating subscription plan. ' . $e->getMessage());
        }
    }
    /**get active subscription */
    public function getCustomerActiveSubscription($companyid)
    {
        return CashierSubscription::where('user_id', $companyid)->whereNull('ends_at')->orderBy('id','desc')->first();
    }
    /**cancel subscription plan */
    public function cancelSubscriptionPlan($request)
    {

        $user = User::find(auth()->user()->id);
        $plan  = SubscriptionPlan::find(auth()->user()->planid);

        try {
            $subscription = $this->getCustomerActiveSubscription(auth()->user()->id);
            $user->subscription($subscription->name)->cancelNow();
            //cancelled_date
            $user->cancelled_date = Carbon::now();
            $user->is_plan_active = 2;
            $user->save();

            Mail::to(env('ADMIN_MAIL'))->send(new CancelSubscriptionMail($user, $plan));

            return true;
        } catch (Exception $e) {
            throw new Exception('Error on cancel subscription plan. ' . $e->getMessage());
        }
    }
    /**check currect any subscription is active or not */

    public function cancelSubscriptionByAdmin($request)
    {
        try {

            $user = User::where('uuid', $request->user_uuid)->first();

            $subscription = $this->getCustomerActiveSubscription($user->id);

            // $user = User::find($user->id);
            $user->subscription($subscription->name)->cancelNow();
            //cancelled_date
            $user->cancelled_date = Carbon::now();
            $user->is_plan_active = 2;
            $user->save();

            return true;
        } catch (Exception $e) {
            throw new Exception('Error on cancel subscription plan. ' . $e->getMessage());
        }
    }
}
