<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueueTicket extends Model
{
    use SoftDeletes;

    // relação de ticket com fila de espera. Um ticket só pode pertencer a uma fila de espera.
    public function queue()
    {
        return $this->belongsTo(Queue::class, 'id_queue');
    }
}
