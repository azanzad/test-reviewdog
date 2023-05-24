<?php
return [
    'app_url' => env('APP_URL'),
    'active' => 1,
    'in_active' => 2,
    'deleted' => 3,
    'status' => [
        '1' => 'Active',
        '2' => 'Inactive',
    ],
    'admin_role' => 1,
    'company_role' => 2,
    'customer_role' => 3,
    'user_roles' => [
        'admin' => 1,
        'company' => 2,
        'customer' => 3,
    ],
    'get_user_roles' => [
        1 => 'admin',
        2 => 'company',
        3 => 'customer',
    ],
    'plan_durations' => [
        1 => 'day',
        2 => 'week',
        3 => 'month',
        4 => 'year',
    ],
    'plan_types' => [
        1 => 'Flat Rate',
        2 => 'Per Store',
    ],
    'customer_types' => [
        '1' => 'Individual Brand',
        '2' => 'Parent Company',
    ],
    'individual_brand' => 1,
    'parent_company' => 2,
    'user_db_name' => 'sterio',
    //allow maximum no. of digit
    'max_mobile' => 10,
    //stripe key
    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    //webhook secret
    'stripe_webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    //default currency
    'default_currency' => 'usd',
    'default_currency_icon' => '$',
    'price_check_conditions' => [
        1 => '=',
        2 => '<',
        3 => '<=',
        4 => '>',
        5 => '>=',
        6 => 'Range',
    ],
    'trial_availability' => [
        0 => 'No',
        1 => 'Yes',
    ],
    'individual_company' => 'User',
    'email_periods' => [
        1 => 'Daily',
        2 => 'Weekly',
        3 => 'Monthly',
        4 => "Don't Send",
    ],
    "is_trial" => 1,
    "trial_day" => 30,
    "is_primary" => 1,
];
