<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TicketCallerController extends Controller
{
    public function index()
    {
        $company = Company::where('id', auth()->user()->id_company)
            ->select('company_name')->first();

        // get all queues and their tickets, total ticket count, total ticket count by status
        $queues = Queue::with('tickets')->withTrashed()->withCount([
            'tickets as total_tickets' => function ($query) {
                $query->where('deleted_at', null);
            },
            'tickets as total_waiting' => function ($query) {
                $query->where('queue_ticket_status', 'waiting');
            },
            'tickets as total_called' => function ($query) {
                $query->where('queue_ticket_status', 'called');
            },
            'tickets as total_not_attended' => function ($query) {
                $query->where('queue_ticket_status', 'not_attended');
            },
            'tickets as total_dismissed' => function ($query) {
                $query->where('queue_ticket_status', 'dismissed');
            },
        ])
        ->where('id_company', auth()->user()->id_company)
        ->orderBy('id', 'desc')->get();

        $data = [
            'subtitle' => 'Chamadas',
            'company' => $company,
            'queues' => $queues,
        ];


        return view('ticket_caller.home', $data);
    }

    public function queueDetails($id)
    {
        // check if the decrypted id is a valid queue
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('caller.home');
        }

        // get the queue and their tickets, total tickets, etc
        $queue = Queue::with('tickets')->withTrashed()->withCount([
            'tickets as total_tickets' => function ($query) {
                $query->where('deleted_at', null);
            },
            'tickets as total_waiting' => function ($query) {
                $query->where('queue_ticket_status', 'waiting');
            },
            'tickets as total_called' => function ($query) {
                $query->where('queue_ticket_status', 'called');
            },
            'tickets as total_not_attended' => function ($query) {
                $query->where('queue_ticket_status', 'not_attended');
            },
            'tickets as total_dismissed' => function ($query) {
                $query->where('queue_ticket_status', 'dismissed');
            },
        ])
        ->where('id', $id)
        ->where('id_company', auth()->user()->id_company)
        ->first();

        if (!$queue) {
            return redirect()->route('caller.home');
        }

        // get the last ticket of the queue
        $lastTicket = $queue->tickets()
            ->where('queue_ticket_status', 'called')
            ->where('deleted_at', null)
            ->orderBy('id', 'desc')->first();

        // get the next ticket of the queue
        $nextTicket = $queue->tickets()
            ->where('queue_ticket_status', 'called')
            ->where('deleted_at', null)
            ->orderBy('id', 'asc')->first();


        // get company name
        $company = Company::where('id', auth()->user()->id_company)
            ->select('company_name')->first();

        $data = [
            'subtitle' => 'Detalhes da fila',
            'company' => $company,
            'queue' => $queue,
            'lastTicket' => $lastTicket,
            'nextTicket' => $nextTicket,
        ];

        return view('ticket_caller.queue_details', $data);
    }
}
