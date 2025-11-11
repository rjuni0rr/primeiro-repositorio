<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueueTicket extends Model
{
    use SoftDeletes;

    // Realação entre Ticket e Queue
    public function queue()
    {
        return $this->belongsTo(User::class, 'id_queue');
    }
}
