<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'price',
        'time',
        'barbershop_id',
    ];

    protected $casts = [
        'barbershop_id' => 'integer',
    ];
    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class, 'barbershop_id');
    }
}
