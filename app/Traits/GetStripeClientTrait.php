<?php

namespace App\Traits;

use Stripe\StripeClient;

trait GetStripeClientTrait
{
    public function getStripeClient()
    {
        return new StripeClient(config('params.stripe.secret'));
    }

}