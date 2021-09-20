<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    public $timestamps = false;

    use HasFactory;

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }
}
