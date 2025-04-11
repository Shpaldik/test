<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'line', 
        'from_place', 
        'to_place',
        'departure_time', 
        'arrival_time', 
        'distance', 
        'speed', 
        'status'
    ];
}
