<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticable
{
    use SoftDeletes;

    // Realação entre User e Company
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
