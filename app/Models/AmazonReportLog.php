<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonReportLog extends Model
{
    use HasFactory;


    public static function getAmazonReportLogByRequestedDate($userId, $storeId, $reportType = null, $requestedDate)
    {
        return self::where('user_id', $userId)
            ->where('store_id', $storeId)
            // ->where('is_processed', '0')
            ->where('requested_date', $requestedDate)
            ->whereDate('created_at', date('Y-m-d'))
            ->where('report_type', !empty($reportType) ? $reportType : 'GET_MERCHANT_LISTINGS_DATA')
            ->first();
    }

    public static function getAmazonReportLogByRequestedDateForDownload($userId, $storeId, $reportType = null, $requestedDate)
    {
        return self::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->where('is_processed', '0')
            ->where('requested_date', $requestedDate)
            ->whereDate('created_at', date('Y-m-d'))
            // ->whereRaw('ABS(TIMESTAMPDIFF(MINUTE, ?, created_at)) >= 30', Now())
            ->where('report_type', !empty($reportType) ? $reportType : 'GET_MERCHANT_LISTINGS_DATA')
            ->first();
    }
}
