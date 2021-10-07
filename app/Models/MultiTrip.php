<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiTrip extends Model
{
    public $timestamps = false;

    use HasFactory;
    public function transportation_mode()
    {
        return $this->belongsTo(TransportationMode::class);
    }
    public function respondent()
    {
        return $this->belongsTo(Respondent::class);
    }
}
