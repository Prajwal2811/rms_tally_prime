<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Owner extends Authenticatable
{
    use HasFactory;

    protected $table = 'rms_owners';
    protected $fillable = [
        'owner_name',
        'email',
        'phone',
        'business_name',
        'business_type',
        'address',
        'password',
        'pass',
        'status',
        'is_subscribed',
        'subscription_expiry',
    ];

    protected $hidden = [
        'password',
    ];
}