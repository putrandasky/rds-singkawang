<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    use HasFactory;

    public function step()
    {
        return $this->belongsTo(Step::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function income()
    {
        return $this->belongsTo(Income::class);
    }
}
