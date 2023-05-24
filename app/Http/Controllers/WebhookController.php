<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Models\SubscriptionPlan;
use Laravel\Cashier\Subscription;
use App\Models\PaymentTransaction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;

class WebhookController extends Controller
{
    /**
     * Create a new WebhookController instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (config('cashier.webhook.secret')) {
            $this->middleware(VerifyWebhookSignature::class);
        }
    }
    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle' . Str::studly(str_replace('.', '_', $payload['type']));

        WebhookReceived::dispatch($payload);

        if (method_exists($this, $method)) {
            $response = $this->{$method}($payload);

            WebhookHandled::dispatch($payload);

            return $response;
        }

        return $this->missingMethod();
    }
    /**
     * Handle paid action required for invoice.
     *
     * @param  string|null  $stripeId
     * @return \Laravel\Cashier\Billable|null
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {

        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {

            $data = $payload['data']['object'];
            $lineData = $data['lines']['data'][0];

            // Payment History
            $dbPlan = SubscriptionPlan::where('stripe_priceid', $lineData['plan']['id'])->first();
            //if user not in trial period
            if ($data['amount_paid'] > 0) {
                //Payment Transaction
                $paymentTransaction = [
                    'user_id' => $user->id,
                    "customer_id" => $data['customer'],
                    'plan_id' => $dbPlan->id,
                    'plan_interval' => $lineData['plan']['interval'],
                    'amount' => $data['total'] / 100,
                    'is_paid' => '1',
                    'currency' => $data['currency'],
                    'transaction_status' => $data['status'],
                    'transaction_id' => $data['payment_intent'],
                    'transaction_date' => date("Y-m-d H:i:s", $data['created']),
                ];
                PaymentTransaction::create($paymentTransaction);
            }
            //update next_billing_date
            $customer = User::where('stripe_id', $user->stripe_id)->first();
            $subscription_id = $customer->subscription_id;
            $customer->next_billing_date = date("Y-m-d H:i:s", $lineData['period']['end']);
            $customer->is_plan_active = 1;
            $customer->current_paid_status = $data['status'];
            $customer->save();
            //update subscription status
            DB::table('subscriptions')
                ->where('id', $subscription_id)
                ->update(array('stripe_status' => 'active'));

        }

        return $this->successMethod();
    }

    /**
     * Handle paid action required for invoice.
     *
     * @param  string|null  $stripeId
     * @return \Laravel\Cashier\Billable|null
     */
    protected function handleInvoicePaymentFailed(array $payload)
    {

        if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
            $data = $payload['data']['object'];
            $lineData = $data['lines']['data'][0];

            // Payment History
            $dbPlan = SubscriptionPlan::where('stripe_priceid', $lineData['plan']['id'])->first();

            //Payment Transaction
            $paymentTransaction = [
                'user_id' => $user->id,
                "customer_id" => $data['customer'],
                'plan_id' => $dbPlan->id,
                'plan_interval' => $lineData['plan']['interval'],
                'amount' => $data['total'] / 100,
                'is_paid' => 0,
                'currency' => $data['currency'],
                'transaction_status' => $data['status'],
                'transaction_id' => $data['payment_intent'],
                'transaction_date' => date("Y-m-d H:i:s", $data['created']),

            ];
            PaymentTransaction::create($paymentTransaction);
            //Cancel subscription if payment failed
            if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
                $user->subscriptions->filter(function ($subscription) use ($payload) {
                    return $subscription->stripe_id === $payload['data']['object']['id'];
                })->each(function ($subscription) {
                    $subscription->markAsCancelled();
                });
            }

            $customer = User::where('stripe_id', $user->stripe_id)->first();
            $customer->is_plan_active = 2;
            $customer->current_paid_status = $data['status'];
            $customer->cancelled_date = date("Y-m-d");
            $customer->save();

        }

        return $this->successMethod();
    }

    /**
     * Handle deleted customer.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerDeleted(array $payload)
    {
        if ($user = $this->getUserByStripeId($payload['data']['object']['id'])) {
            $user->subscriptions->each(function (Subscription $subscription) {
                $subscription->skipTrial()->markAsCancelled();
            });

            $user->forceFill([
                'stripe_id' => null,
                'trial_ends_at' => null,
                'card_brand' => null,
                'card_last_four' => null,
            ])->save();
        }

        return $this->successMethod();
    }
    /**
     * Get the billable entity instance by Stripe ID.
     *
     * @param  string|null  $stripeId
     * @return \Laravel\Cashier\Billable|null
     */
    protected function getUserByStripeId($stripeId)
    {
        return Cashier::findBillable($stripeId);
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function successMethod($parameters = [])
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function missingMethod($parameters = [])
    {
        return new Response;
    }
}
