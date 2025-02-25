<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
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

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
            set: fn($value) => strtolower($value)
        );
    }


    public function barbershops()
    {
        return $this->hasMany(Barbershop::class, 'owner_id');
    }
}
