<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'phone' => 'string'
    ];

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
            set: function ($value) {
                $cleaned = preg_replace('/[^0-9]/', '', $value);

                if (strlen($cleaned) === 11) {
                    return '(' . substr($cleaned, 0, 2) . ') '
                        . substr($cleaned, 2, 5) . '-'
                        . substr($cleaned, 7, 4);
                } elseif (strlen($cleaned) === 10) {
                    return '(' . substr($cleaned, 0, 2) . ') '
                        . substr($cleaned, 2, 4) . '-'
                        . substr($cleaned, 6, 4);
                }
                return $cleaned;
            }
        );
    }
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
            set: fn($value) => ucwords(strtolower($value))
        );
    }
    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
            set: fn($value) => ucwords(strtolower($value))
        );
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
