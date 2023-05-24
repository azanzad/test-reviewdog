<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = "countries";


    /**
     * Get country timezone
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timezone()
    {
        return $this->hasOne(Timezone::class, 'country_code', 'country_code');
    }
}
