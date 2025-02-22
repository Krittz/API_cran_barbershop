<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barbershop extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'owner_id',
    ];

    protected $casts = [
        'owner_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
