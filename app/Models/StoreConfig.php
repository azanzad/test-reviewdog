<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StoreConfig extends Model
{
    use HasFactory;

    public static function getAmazonStoreTypes($array = [])
    {
        $amazonStoreTypes = self::select('store_type')->where('store_type', '!=', 'Amazon CN')
            ->orderBy(DB::raw('CAST(store_type AS CHAR)'), 'ASC');

        return $amazonStoreTypes->pluck('store_type', 'store_type');
    }
}