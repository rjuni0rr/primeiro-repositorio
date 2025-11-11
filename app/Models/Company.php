<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    // Realação entre Company e User
    public function users()
    {
        return $this->hasMany(User::class, 'id_company');
    }

    // Realação entre Company e Queue
    public function queues()
    {
        return $this->hasMany(Queue::class, 'id_company');
    }
}
