<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bowser extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'vehicle_no',
        'capacity',
        'curent_location',
    ];
}
