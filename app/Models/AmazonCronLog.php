<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonCronLog extends Model
{
    use HasFactory;
    protected $fillable = ['store_id', 'amazon_feed_id', 'cron_name', 'cron_type', 'cron_param', 'start_time', 'end_time'];

    public static function cronStartEndUpdate($cron)
    {
        // If action is start
        if ($cron['action'] == 'start') {
            $cron['start_time'] = CommonHelper::getInsertedDateTime();

            return self::create($cron);
        }
    }

    /* Update cron end_time */
    public function updateEndTime()
    {
        $this->update(['end_time' => CommonHelper::getInsertedDateTime()]);
    }

}