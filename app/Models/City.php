<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    use HasFactory;
    /**
     * Get all of the respondents for the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }
}
