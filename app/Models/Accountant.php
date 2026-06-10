<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accountant extends Model
{
    use HasFactory;

    protected $table = 'rms_accountants';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'pass',
        'status'
    ];

    protected $hidden = [
        'password',
        'pass'
    ];

    public function collectors()
    {
        return $this->hasMany(Collector::class, 'accountant_id');
    }
}