<?php

namespace App\Helpers;

class MarketPlaceHelper
{
    public static function groupMapping()
    {
        return [
            "Amazon UK" => "uk_group",
            "Amazon ES" => "uk_group",
            "Amazon FR" => "uk_group",
            "Amazon DE" => "uk_group",
            "Amazon IT" => "uk_group",
        ];
    }

    public static function configuration($key = '')
    {
        $amazonState = "state-" . uniqid();
        $url = "apps/authorize/consent?application_id=" . config('amazon.AWS_APPLICATION_ID') . "&state=" . $amazonState;
        // if (config('amazon.AWS_BETA_VERSION')) {
        //     $url .= "&version=beta";
        // }

        $array = [
            "Amazon US" => array(
                "seller_central_link" => "https://sellercentral.amazon.com/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon CA" => array(
                "seller_central_link" => "https://sellercentral.amazon.ca/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon MX" => array(
                "seller_central_link" => "https://sellercentral.amazon.com.mx/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon UK" => array(
                "seller_central_link" => "https://sellercentral.amazon.co.uk/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon ES" => array(
                "seller_central_link" => "https://sellercentral.amazon.es/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon FR" => array(
                "seller_central_link" => "https://sellercentral.amazon.fr/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon DE" => array(
                "seller_central_link" => "https://sellercentral.amazon.de/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon IT" => array(
                "seller_central_link" => "https://sellercentral.amazon.it/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon JP" => array(
                "seller_central_link" => "https://sellercentral-japan.amazon.com/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon CN" => array(
                "seller_central_link" => "https://mai.amazon.cn/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
            "Amazon IN" => array(
                "seller_central_link" => "https://sellercentral.amazon.in/" . $url,
                "developer_account_number" => "4867-6042-1113",
                "mws_access_key_id" => config('amazon.MWS_CLIENT_ID'),
                "mws_secret_key" => config('amazon.MWS_CLIENT_SECRET'),
                "aws_access_key_id" => config('amazon.AWS_ACCESS_KEY'),
                "aws_secret_key" => config('amazon.AWS_SECRET_KEY'),
            ),
        ];

        return !empty($key) ? $array[$key] : $array;
    }
}