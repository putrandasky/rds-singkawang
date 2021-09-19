<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class respondent extends Model
{
    use HasFactory;

    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}
