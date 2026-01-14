<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bundle extends Model
{
    use SoftDeletes;

    // relação entre bundles e empresas. Um bundle só pode peretencer a uma empresa (company)
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
