<?php
namespace App\Services;

use App\Models\CustomerCard;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Traits\GetStripeClientTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class CompanyCardService
{
    use GetStripeClientTrait;
    /**
     * getData function
     *
     * @param Request $request
     * @return object
     */
    public function getData(Request $request)
    {
        return CustomerCard::where('companyid', auth()->user()->id);
    }
    /**
     * storeCard function
     *
     * @param Request $request
     * @return object
     */
    public function storeCard(Request $request)
    {
        //add card to on stripe account, if added success on stripe then add in our db
        $stripe_card = $this->saveCardOnStripe($request);
        if ($stripe_card) {
            $card = new CustomerCard;
            $card->uuid = Str::uuid();
            $card->companyid = auth()->user()->id;
            $card->stripe_customerid = auth()->user()->stripe_id;
            $card->stripe_cardid = $stripe_card->id;
            $card->brand = $stripe_card->brand;
            $card->stripe_token = $request->stripeToken;
            $card->last_number = $stripe_card->last4;
            $card->cardholder_name = $request->name;
            $card->is_primary = $this->getCustomerCardcount() ? 0 : 1;
            $card->save();
            //make payment if type is payment
            if ($request->type == 'payment') {
                $user = (new CompanyService())->getCompany(auth()->user()->uuid);
                //active subscription, if added success on stripe then add in our db
                $subscription = $this->activeSubscriptionPlanOnStripe($request, $user);
                if (!empty($subscription)) {
                    $this->updateUserSubscriptionDetails($subscription, $user);
                }
            }
            return $card;
        }
    }
    /**
     * makePayment function
     *
     * @param Request $request
     * @return object
     */
    public function makePayment(Request $request)
    {
        $user = User::find(auth()->user()->id);
        try {
            $card = CustomerCard::find($request->payment_method);
            $subscription = $this->activeSubscriptionPlanOnStripe($request, $card);
            if (!empty($subscription)) {
                //set default card to our db
                CustomerCard::where('id', '!=', $request->payment_method)->where('companyid', auth()->user()->id)->update(['is_primary' => 0]);
                $card->is_primary = 1;
                $card->save();
                $this->updateUserSubscriptionDetails($subscription, $user);
            }
            return $subscription;
        } catch (Exception $e) {
            throw new Exception('Error on payment. ' . $e->getMessage());
        }
    }
    /**
     * getCustomerCardcount function
     *
     * @return object
     */
    public function getCustomerCardcount()
    {
        return CustomerCard::where('companyid', auth()->user()->id)->count();
    }
    /**
     * getCard function
     *
     * @param string $uuid
     * @return object
     */
    public function getCard(string $uuid)
    {
        return CustomerCard::where('uuid', $uuid)->first();
    }
    /**
     * updateDefaultCard function
     *
     * @param Request $request
     * @param string $uuid
     * @return object
     */
    public function updateDefaultCard(Request $request)
    {
        $card = $this->getCard($request->uuid);
        if ($card) {
            $is_update = $this->setDefaultCardOnStripe($card->stripe_cardid);
            if ($is_update) {
                //remove old default card
                CustomerCard::where('uuid', '!=', $request->uuid)->where('companyid', auth()->user()->id)->update(['is_primary' => 0]);
                $card->is_primary = 1;
                $card->save();
            }
        }
        return $card;
    }
    /**
     * deleteCard function
     *
     * @param string $uuid
     * @return boolean
     */
    public function deleteCompanyCard(string $uuid)
    {
        $card = $this->getCard($uuid);
        if ($card->stripe_cardid) {
            $this->deleteCardOnStripe($card->stripe_cardid);
        }
        $card->status = config('params.deleted');
        $card->save();
        $card->delete();
        return true;
    }
    /**
     * getCustomerDefaultCard function
     *
     * @param integer $companyid
     * @return object
     */
    public function getCustomerDefaultCard(int $companyid)
    {
        return CustomerCard::where([['companyid', $companyid], ['is_primary', 1]])->first();
    }
    /** save card on stripe account function */
    public function saveCardOnStripe(Request $request)
    {
        $stripe = $this->getStripeClient();
        try {
            $card = $stripe->customers->createSource(
                auth()->user()->stripe_id,
                ['source' => $request->stripeToken,
                ]);
            return $card;
        } catch (Exception $e) {
            throw new Exception('Error on add Card. ' . $e->getMessage());
        }
    }
    /**delete card from stripe */
    public function deleteCardOnStripe(string $cardid)
    {
        $stripe = $this->getStripeClient();
        try {
            return $stripe->customers->deleteSource(auth()->user()->stripe_id, $cardid, []);
        } catch (Exception $e) {
            throw new Exception('Error on deleting card. ' . $e->getMessage());
        }

    }
    /**set default card on stripe */
    public function setDefaultCardOnStripe(string $cardid)
    {
        $stripe = $this->getStripeClient();
        try {
            return $stripe->customers->update(
                auth()->user()->stripe_id,
                ['default_source' => $cardid]
            );
        } catch (Exception $e) {
            throw new Exception('Error on set default card. ' . $e->getMessage());
        }
    }
    /*** active subcription with stripe payment*/
    public function activeSubscriptionPlanOnStripe($request, $card = null)
    {
        $user = User::find(auth()->user()->id);

        $plan = SubscriptionPlan::find(auth()->user()->planid);

        try {
            if (empty($card)) {
                $card = $this->getCustomerDefaultCard(auth()->user()->id);
            }
            $stripe_cardid = !empty($card) ? $card->stripe_cardid : '';

            if(isset($request->promotion_token) && !empty($request->promotion_token)){

                $decrypted = Crypt::decryptString($request->promotion_token);
                $promotionArr = json_decode($decrypted);


                if($plan->trial_days > 0){

                    $subacription = $user->newSubscription($plan->name, $plan->stripe_priceid)
                            ->trialDays($plan->trial_days)
                            ->withPromotionCode($promotionArr->promotion_id)
                            ->create($stripe_cardid);

                }else{

                    $subacription = $user->newSubscription($plan->name, $plan->stripe_priceid)
                            ->withPromotionCode($promotionArr->promotion_id)
                            ->create($stripe_cardid);
                }

                if($subacription){

                    $user->promo_code = $promotionArr->promotion_code;
                    $user->promo_code_id = $promotionArr->promotion_id;
                    $user->discount_price =$promotionArr->discount_amount;
                    $user->save();
                }

            }else{

                if($plan->trial_days > 0){

                    $subacription = $user->newSubscription($plan->name, $plan->stripe_priceid)
                            ->trialDays($plan->trial_days)
                            ->create($stripe_cardid);

                } else {

                    $subacription = $user->newSubscription($plan->name, $plan->stripe_priceid)->create($stripe_cardid);
                }
            }

            return $subacription;
        } catch (Exception $e) {
            throw new Exception('Error on payment. ' . $e->getMessage());
        }
    }
    /**update subscription details in users table */
    public function updateUserSubscriptionDetails($subscription, $user)
    {
        $user->subscription_id = $subscription->id;
        $user->subscription_item_id = $subscription->items[0] ? $subscription->items[0]['id'] : '';
        $user->is_plan_active = 1;
        $user->trial_ends_at = $subscription->trial_ends_at;
        $user->currency = $subscription->currency;
        $user->activated_date = $subscription->start_date;
        $user->save();
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param string $stripe_customerid
     * @return object
     */
    // public function saveUserCardDetailsOnStripe(Request $request, string $stripe_customerid)
    // {
    //     $stripe = $this->getStripeClient();
    //     try {
    //         $card = $stripe->customers->createSource(
    //             $stripe_customerid,
    //             ['source' => $request->stripeToken,
    //             ]);
    //         return $card;
    //     } catch (Exception $e) {
    //         throw new Exception('Error on add Card. ' . $e->getMessage());
    //     }
    // }

    /**
     * activeUserSubscriptionPlanOnStripe function
     *
     * @param object $data
     * @param [type] $card
     * @return void
     */
    // public function activeUserSubscriptionPlanOnStripe($user)
    // {

    //     try {

    //         $card = $this->getCustomerDefaultCard($user->id);
    //         $stripe_cardid = !empty($card) ? $card->stripe_cardid : '';

    //         if ($user->is_trial) {
    //             $trial_days = $user->trial_days;

    //             $subacription = $user->newSubscription(
    //                 $user->getPlan->name, $user->getPlan->stripe_priceid
    //             )->trialDays($trial_days)
    //             ->withPromotionCode('promo_1MWgLbLVpayYT6EOlLxRbAIE')
    //             ->create($card->stripe_cardid);

    //         } else {
    //             $subacription = $user->newSubscription(
    //                 $user->getPlan->name, $user->getPlan->stripe_priceid
    //             )->create($card->stripe_cardid);
    //         }
    //         return $subacription;
    //     } catch (Exception $e) {
    //         throw new Exception('Error on payment. ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * storeUserCard function
    //  *
    //  * @param Request $request
    //  * @return response
    //  */
    // public function storeUserCard(Request $request){

    //     try{
    //         $user = User::find(auth()->user()->id);
    //         $stripe_card = (new CompanyCardService)->saveUserCardDetailsOnStripe($request, $user->stripe_id);

    //         if(!empty($stripe_card)){

    //             $userCard = [
    //                 'uuid'      => Str::uuid(),
    //                 'companyid' => $user->id,
    //                 'stripe_customerid' => $user->stripe_id,
    //                 'stripe_cardid' => $stripe_card->id,
    //                 'brand'     => $stripe_card->brand,
    //                 'stripe_token' => $request->stripeToken,
    //                 'last_number' => $stripe_card->last4,
    //                 'cardholder_name' => $request->card_holder_name,
    //                 'is_primary' => config('params.is_primary'),
    //             ];
    //             CustomerCard::create($userCard);

    //             $subscription = (new CompanyCardService())->activeUserSubscriptionPlanOnStripe($user);
    //             if (!empty($subscription)) {
    //                 (new CompanyCardService())->updateUserSubscriptionDetails($subscription, $user);
    //             }
    //         }

    //     } catch (Exception $e) {
    //         throw new Exception('Error on storeusercard. ' . $e->getMessage());
    //     }
    // }

    /**
     * findActivePromotionCode function : check promotion code is correct or not
     *
     * @param string $promotion_code
     * @return object
     */
    public function findActivePromotionCode(string $promotion_code){

        $user = User::find(auth()->user()->id);
        $promotionCode = $user->findActivePromotionCode($promotion_code);
        return $promotionCode;
    }
}
