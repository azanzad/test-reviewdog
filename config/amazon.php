<?php

use Carbon\Carbon;

return [
    'ACTIVE' => 1,
    'AWS_APPLICATION_ID' => 'amzn1.sp.solution.b87cd820-e2b1-4a0d-aeb6-5cef8b1e21d6',
    'AWS_BETA_VERSION' => '',
    'MWS_CLIENT_ID' => 'amzn1.application-oa2-client.8ec0568a5443487699750f664e324ea2',
    'MWS_CLIENT_SECRET' => '128a182f5f9e46017bf84e88bac5f60fd0297ee495eeed9c570abdb607eb90d3',
    'AWS_ACCESS_KEY' => 'AKIARESQZHU5NIDOXG4Q',
    'AWS_SECRET_KEY' => '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',
    "DATEFORMAT" => 'd-M-Y H:i A',
    "DEFAULT_PER_PAGE" => 10,
    'INSERT_DATE_FORMAT' => 'Y-m-d H:i:s',
    'ORDER_FETCHING_LAST_HOURS' => '5',
    'ORDER_FETCHING_START_DATE' => Carbon::now()->subDays(30)->format('Y-m-d 00:00:00'),
    "PER_PAGE" => [10 => 10, 25 => 25, 50 => 50, 100 => 100],
    'SYSTEM_CURRENCY' => 'USD',
    'MAX_MARKETPLACE_CRON_ATTEMPTS' => '2',
    'NOTIFICATION_EMAIL' => 'jignesh@topsinfosolutions.com',
];