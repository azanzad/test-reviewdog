<?php

namespace App\Helpers;

use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CommonHelper
{
    public static function getInsertedDateTime()
    {
        return Carbon::now()->format(config('amazon.INSERT_DATE_FORMAT'));

    }

    /**
     * Return array of last 15 days date staring from yesterday
     */
    public static function getLastFifteenDaysDatesArray()
    {
        $todayDate = date('Y-m-d', strtotime("+1 days"));
        $last15thDayDate = date('Y-m-d', strtotime("-400 days"));

        $begin = new DateTime($last15thDayDate);
        $end = new DateTime($todayDate);

        $fifteenDaysDateArray = [];

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $fifteenDaysDate = [];
        foreach ($period as $dt) {
            $fifteenDaysDate[] = $dt->format("Y-m-d");
        }
        return $fifteenDaysDate;
    }

    public static function getValue($variable, $keys = false, $default = null, $callable = false, $is_object = false)
    {
        if ($is_object) {
            // To build
        } else {
            if (is_array($keys)) {
                // Do nothing
            } else {
                $keys = explode('|', $keys);
            }

            $value = $variable;

            foreach ($keys as $key) {
                if (isset($value[$key])) {
                    if ($key == end($keys)) {
                        if ($callable && is_callable($callable)) {
                            return $callable($value[$key]);
                        } else {
                            return $value[$key];
                        }
                    } else {
                        $value = $value[$key];
                    }
                } else {
                    break;
                }
            }
        }

        return $default;
    }

    public static function extractFileContent($file_content, $report_id)
    {
        if (!Storage::exists('public/temp/')) {
            Storage::makeDirectory('public/temp/', 0777, true);
        }

        $report_folder = storage_path("app/public/temp/");

        $zipFile = $report_folder . $report_id . '.gz';

        $feedHandle = fopen($zipFile, 'w');

        fclose($feedHandle);

        $feedHandle = fopen($zipFile, 'rw+');

        fwrite($feedHandle, $file_content);

        $gz = gzopen($zipFile, 'rb');

        $file_name = $report_folder . $report_id . '.txt';

        $dest = fopen($file_name, 'wb');

        stream_copy_to_stream($gz, $dest);

        gzclose($gz);

        fclose($dest);

        $report_data = file_get_contents($file_name);

        if (Storage::disk('public')->exists('temp/' . $report_id . '.txt')) {
            Storage::disk('public')->delete('temp/' . $report_id . '.txt');
        }

        if (Storage::disk('public')->exists('temp/' . $report_id . '.gz')) {
            Storage::disk('public')->delete('temp/' . $report_id . '.gz');
        }
        return $report_data;
    }
}
