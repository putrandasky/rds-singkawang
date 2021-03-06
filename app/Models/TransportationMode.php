<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationMode extends Model
{
    public $timestamps = false;

    use HasFactory;
    public function multi_trips()
    {
        return $this->hasMany(MultiTrip::class);
    }
}
