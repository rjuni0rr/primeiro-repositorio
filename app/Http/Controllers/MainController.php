<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Company;
use App\Models\QueueTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function index()
    {
        // get list of active queues for the authenticated user's company
        $data = [
            'subtitle' => 'Home',
            'queues' => $this->getQueuesList(),
            'companyName' => Auth::user()->company->company_name,
            'companyTotal' => $this->getCompanyTotals()
        ];

        return view('main.home', $data);
    }

    private function getQueuesList()
    {
        $companyId = Auth::user()->id_company;

        return Queue::withTrashed()
            ->where('id_company', $companyId)
            ->withCount([
                'tickets as total_tickets' => function($query) {
                    $query->whereNotNull('queue_ticket_status');
                },
                'tickets as total_dismissed' => function($query) {
                    $query->where('queue_ticket_status', 'dismissed');
                },
                'tickets as total_not_attended' => function($query) {
                    $query->where('queue_ticket_status', 'not_attended');
                },
                'tickets as total_called' => function($query) {
                    $query->where('queue_ticket_status', 'called');
                },
                'tickets as total_waiting' => function($query) {
                    $query->where('queue_ticket_status', 'waiting');
                },
            ])
            ->get();
    }

    private function getCompanyTotals()
    {
        $companyId = Auth::user()->id_company;
        $totalQueues = Queue::where('id_company', $companyId)->count();

        // get all tickets of the company
        $tickets = QueueTicket::whereHas('queue', function($query) use ($companyId){
            $query->where('id_company', $companyId);
        })->get();

        return [
            'total_queues' => $totalQueues,
            'total_tickets' => $tickets->count(),
            'total_dismissed' => $tickets->where('queue_ticket_status', 'dismissed')->count(),
            'total_not_attended' => $tickets->where('queue_ticket_status', 'not_attended')->count(),
            'total_called' => $tickets->where('queue_ticket_status', 'called')->count(),
            'total_waiting' => $tickets->where('queue_ticket_status', 'waiting')->count(),
        ];
    }

    public function queueDetails($id)
    {
        // try to decrypt the id
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(403, 'ID de fila inválido');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->withCount([
                'tickets as total_tickets' => function($query){
                    $query->whereNotNull('queue_ticket_status')
                        ->whereNull('deleted_at');
                },
                'tickets as total_dismissed' => function($query){
                    $query->where('queue_ticket_status', 'dismissed')
                        ->whereNull('deleted_at');
                },
                'tickets as total_not_attended' => function($query){
                    $query->where('queue_ticket_status', 'not_attended')
                        ->whereNull('deleted_at');
                },
                'tickets as total_called' => function($query){
                    $query->where('queue_ticket_status', 'called')
                        ->whereNull('deleted_at');
                },
                'tickets as total_waiting' => function($query){
                    $query->where('queue_ticket_status', 'waiting')
                        ->whereNull('deleted_at');
                },
            ])
            ->firstOrFail();

        if(!$queue) {
            abort(404, 'Fila não encontrada');
        }

        // get the tickets from the queue
        $tickets = $queue->tickets()->get();

        $data = [
            'subtitle' => 'Detalhes',
            'queue' => $queue,
            'tickets' => $tickets
        ];

        return view('main.queue_details', $data);
    }

    public function createQueue()
    {
        $data = [
            'subtitle' => 'Criar fila'
        ];

        return view('main.queue_create_frm', $data);
    }

    public function createQueueSubmit(Request $request)
    {
        // validate the request
        $request->validate(
            [
                'name' => 'required|min:5|max:100',
                'description' => 'required|min:5|max:255',
                'service' => 'required|min:3|max:50',
                'desk' => 'required|min:1|max:20',
                'prefix' => 'required|regex:/^[A-Z\-]{1}$/',
                'total_digits' => 'required|integer|min:2|max:4',
                'color_1' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'color_2' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'color_3' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'color_4' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'hidden_hash_code' => 'required|size:64',
                'status' => 'required|in:active,inactive',
            ],
            [
                'name.required' => 'O nome da fila é obrigatório.',
                'name.min' => 'O nome da fila deve ter pelo menos 5 caracteres.',
                'name.max' => 'O nome da fila não pode ter mais de 100 caracteres.',
                'description.required' => 'A descrição da fila é obrigatória.',
                'description.min' => 'A descrição da fila deve ter pelo menos 5 caracteres.',
                'description.max' => 'A descrição da fila não pode ter mais de 255 caracteres.',
                'service.required' => 'O serviço é obrigatório.',
                'service.min' => 'O serviço deve ter pelo menos 3 caracteres.',
                'service.max' => 'O serviço não pode ter mais de 50 caracteres.',
                'desk.required' => 'O balcão é obrigatório.',
                'desk.min' => 'O balcão deve ter pelo menos 1 caractere.',
                'desk.max' => 'O balcão não pode ter mais de 20 caracteres.',
                'prefix.required' => 'O prefixo é obrigatório.',
                'prefix.regex' => 'O prefixo não tem o valor correto.',
                'total_digits.required' => 'O total de dígitos é obrigatório.',
                'total_digits.integer' => 'O total de dígitos deve ser um número inteiro.',
                'total_digits.min' => 'O total de dígitos deve ser pelo menos 2.',
                'total_digits.max' => 'O total de dígitos não pode ser mais que 4.',
                'color_1.required' => 'A cor de fundo do prefixo é obrigatória.',
                'color_1.regex' => 'A cor de fundo do prefixo deve ser um código hexadecimal válido (ex: #ffffff).',
                'color_2.required' => 'A cor do texto do prefixo é obrigatória.',
                'color_2.regex' => 'A cor do texto do prefixo deve ser um código hexadecimal válido (ex: #ffffff).',
                'color_3.required' => 'A cor de fundo do número é obrigatória.',
                'color_3.regex' => 'A cor de fundo do número deve ser um código hexadecimal válido (ex: #ffffff).',
                'color_4.required' => 'A cor do texto do número é obrigatória.',
                'color_4.regex' => 'A cor do texto do número deve ser um código hexadecimal válido (ex: #ffffff).',
                'hidden_hash_code.required' => 'O código hash é obrigatório.',
                'hidden_hash_code.size' => 'O código hash deve ter exatamente 64 caracteres.',
                'status.required' => 'O estado da fila é obrigatório.',
                'status.in' => 'O estado da fila deve ser ativo ou inativo.',
            ]
        );

        // check if the name of the queue is unique in the context of the company
        $companyId = Auth::user()->id_company;
        $queueExists = Queue::where('id_company', $companyId)
            ->where('name', $request->name)
            ->exists();
        if($queueExists){
            return redirect()->back()->withInput()->with(['server_error' => 'Já existe uma fila de espera com esse nome. Por favor defina um nome diferente.']);
        }

        // check again if the hash code is unique
        $hashCode = $request->hidden_hash_code;
        $hashExists = Queue::where('hash_code', $hashCode)->exists();
        if($hashExists){
            return redirect()->back()->withInput()->with(['server_error' => 'O código hash da fila já existe. Por favor gere um novo código.']);
        }

        // prepare the data to be saved
        $newQueue = new Queue();
        $newQueue->id_company = Auth::user()->id_company;
        $newQueue->name = trim($request->name);
        $newQueue->description = trim($request->description);
        $newQueue->service_name = trim($request->service);
        $newQueue->service_desk = trim($request->desk);
        $newQueue->queue_prefix = strtoupper(trim($request->prefix));
        $newQueue->queue_total_digits = (int) trim($request->total_digits);
        $newQueue->queue_colors =  json_encode([
            'prefix_bg_color' => trim($request->color_1),
            'prefix_text_color' => trim($request->color_2),
            'number_bg_color' => trim($request->color_3),
            'number_text_color' => trim($request->color_4),
        ]);
        $newQueue->hash_code = trim($request->hidden_hash_code);
        $newQueue->status = trim($request->status);

        // store the new queue in the database
        $newQueue->save();

        return redirect()->route('home');
    }

    public function generateQueueHash()
    {
        // genetare a unique 64 chars hash code
        $hash = hash('sha256', Str::random(40));

        // make certain that the hash is unique
        while(Queue::where('hash_code', $hash)->exists()){
            $hash = hash('sha256', Str::random(40));
        }

        // return the unique hash code
        return response()->json(['hash' => $hash]);
    }

    public function editQueue($id)
    {
        // check if the decrypted queue ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(403, 'ID de fila inválido.');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if(!$queue){
            abort(404, 'Fila não encontrada.');
        }

        // show the edit queue form
        $data = [
            'subtitle' => 'Editar fila',
            'queue' => $queue,
            'queueColors' => json_decode($queue->queue_colors, true)
        ];

        return view('main.queue_edit_frm', $data);
    }

    public function editQueueSubmit(Request $request)
    {
        // validate the request
        $request->validate(
            [
                'name' => 'required|min:5|max:100',
                'description' => 'required|min:5|max:255',
                'service' => 'required|min:3|max:50',
                'desk' => 'required|min:1|max:20',
                'prefix' => 'required|regex:/^[A-Z\-]{1}$/',
                'color_1' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'color_2' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'color_3' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'color_4' => 'required|regex:/^\#[a-f0-9]{6}$/',
                'status' => 'required|in:active,inactive',
            ],
            [
                'name.required' => 'O nome da fila é obrigatório.',
                'name.min' => 'O nome da fila deve ter pelo menos 5 caracteres.',
                'name.max' => 'O nome da fila não pode ter mais de 100 caracteres.',
                'description.required' => 'A descrição da fila é obrigatória.',
                'description.min' => 'A descrição da fila deve ter pelo menos 5 caracteres.',
                'description.max' => 'A descrição da fila não pode ter mais de 255 caracteres.',
                'service.required' => 'O serviço é obrigatório.',
                'service.min' => 'O serviço deve ter pelo menos 3 caracteres.',
                'service.max' => 'O serviço não pode ter mais de 50 caracteres.',
                'desk.required' => 'O balcão é obrigatório.',
                'desk.min' => 'O balcão deve ter pelo menos 1 caractere.',
                'desk.max' => 'O balcão não pode ter mais de 20 caracteres.',
                'prefix.required' => 'O prefixo é obrigatório.',
                'prefix.regex' => 'O prefixo não tem o valor correto.',
                'color_1.required' => 'A cor de fundo do prefixo é obrigatória.',
                'color_1.regex' => 'A cor de fundo do prefixo deve ser um código hexadecimal válido (ex: #ffffff).',
                'color_2.required' => 'A cor do texto do prefixo é obrigatória.',
                'color_2.regex' => 'A cor do texto do prefixo deve ser um código hexadecimal válido (ex: #ffffff).',
                'color_3.required' => 'A cor de fundo do número é obrigatória.',
                'color_3.regex' => 'A cor de fundo do número deve ser um código hexadecimal válido (ex: #ffffff).',
                'color_4.required' => 'A cor do texto do número é obrigatória.',
                'color_4.regex' => 'A cor do texto do número deve ser um código hexadecimal válido (ex: #ffffff).',
                'status.required' => 'O estado da fila é obrigatório.',
                'status.in' => 'O estado da fila deve ser ativo ou inativo.',
            ]
        );

        // check if queue ID is provided
        if(!$request->has('queue_id')){
            abort(403, 'Operação inválida');
        }

        try {
            Crypt::decrypt($request->queue_id);
        } catch (\Exception $e) {
            abort(403, 'Operação inválida');
        }

        // check if the queue identified belongs to the authenticated user's company
        $queueId = Crypt::decrypt($request->queue_id);
        $companyId = Auth::user()->id_company;

        $queue = Queue::where('id', $queueId)
            ->where('id_company', $companyId)
            ->firstOrFail();

        if(!$queue){
            abort(404, 'Operação inválida');
        }

        // check if the name is unique for the company
        $queueExists = Queue::where('id_company', $companyId)
            ->where('name', $request->name)
            ->where('id', '!=', $queueId)
            ->exists();
        if($queueExists){
            return redirect()->back()->withInput()->with(['server_error' => 'Já existe outra fila com o mesmo nome. Por favor defina outro nome.']);
        }

        // prepare the data to save / update the database
        $queue->name = trim($request->name);
        $queue->description = trim($request->description);
        $queue->service_name = trim($request->service);
        $queue->service_desk = trim($request->desk);
        $queue->queue_prefix = trim($request->prefix);
        $queue->queue_colors = json_encode([
            'prefix_bg_color' => trim($request->color_1),
            'prefix_text_color' => trim($request->color_2),
            'number_bg_color' => trim($request->color_3),
            'number_text_color' => trim($request->color_4),
        ]);
        $queue->status = trim($request->status);

        $queue->save();

        return redirect()->route('home');
    }

    public function cloneQueue($id)
    {
        // check if the decrypted queue ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(403, 'ID de fila inválido.');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if (!$queue) {
            abort(403, 'Fila não encontrada');
        }

        // show the clone queue form
        $data = [
            'subtitle' => 'Clonar fila',
            'queue' => $queue
        ];

        return view('main.queue_clone_frm', $data);
    }

    public function cloneQueueSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'name' => 'required|min:5|max:100'
            ],
            [
                'name.required' => 'O nome da fila é obrigatório.',
                'name.min' => 'O nome da fila deve ter no mínimo 5 caracteres.',
                'name.max' => 'O nome da fila deve ter no máximo 100 caracteres.'
            ]
        );

        // check if the original queue id is present
        if(!$request->has('original_queue_id')){
            abort(403, 'Operação inválida 1.');
        }

        // try to decrypt the original queue id
        try {
            $queueId = Crypt::decrypt($request->original_queue_id);
        } catch (\Exception $e) {
            abort(403, 'Operação inválida 2.');
        }

        // check if the original queue belongs to the authenticated user's company
        $queue = Queue::where('id', $queueId)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if(!$queue) {
            abort(403, 'Operação inválida 3.');
        }

        // check if the name is unique for the company
        $queueExists = Queue::where('name', trim($request->name))
            ->where('id_company', Auth::user()->id_company)
            ->exists();

        if($queueExists) {
            return redirect()->back()->withInput()->with('server_error', 'Já existe outra fila com o mesmo nome. Por favor, defina um nome diferente.');
        }

        // prepare the data to be saved
        $newQueue = new Queue();
        $newQueue->id_company = Auth::user()->id_company;
        $newQueue->name = trim($request->name);
        $newQueue->description = $queue->description;
        $newQueue->service_name = $queue->service_name;
        $newQueue->service_desk = $queue->service_desk;
        $newQueue->queue_prefix = $queue->queue_prefix;
        $newQueue->queue_total_digits = $queue->queue_total_digits;
        $newQueue->queue_colors = $queue->queue_colors;
        $newQueue->status = $queue->status;

        // set a new hash code (unique)
        $hash_code = hash('sha256', Str::random(40));
        while(Queue::where('hash_code', $hash_code)->exists()){
            $hash_code = hash('sha256', Str::random(40));
        }

        $newQueue->hash_code = $hash_code;

        $newQueue->save();

        return redirect()->route('home');
    }

    public function deleteQueue($id)
    {
        // check if the decrypted queue ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(403, 'ID de fila inválido.');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if(!$queue){
            abort(404, 'Fila não encontrada.');
        }

        // show the delete confirmation page
        $data = [
            'subtitle' => 'Eliminar fila',
            'queue' => $queue
        ];

        return view('main.queue_delete', $data);
    }

    public function deleteQueueConfirm($id)
    {
        // check if the decrypted queue ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(403, 'ID de fila inválido.');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if(!$queue){
            abort(404, 'Fila não encontrada.');
        }

        // delete the queue
        $queue->delete();

        return redirect()->route('home');
    }

    public function restoreQueue($id)
    {
        // check if the decrypted queue ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(403, 'ID de fila inválido.');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::withTrashed()
            ->where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if(!$queue){
            abort(404, 'Fila não encontrada.');
        }

        // restore the soft deleted queue
        $queue->restore();

        return redirect()->route('home');
    }

    public function permDeleteQueue($id)
    {
        // check if the decrypted queue ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e){
            abort(403, 'ID de fila inválido.');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::withTrashed()
            ->where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if (!$queue){
            abort(404, 'Fila não encontrada');
        }

        // show the delete confirmation page
        $data = [
            'subtitle' => 'Eliminar Permanente',
            'queue' => $queue
        ];

        return view('main.queue_perm_delete', $data);
    }

    public function permDeleteQueueConfirm($id)
    {
        // check if the decrypted queue ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e){
            abort(403, 'ID de fila inválido.');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $queue = Queue::withTrashed()
            ->where('id', $id)
            ->where('id_company', Auth::user()->id_company)
            ->firstOrFail();

        if (!$queue){
            abort(404, 'Fila não encontrada');
        }

        // restore the soft deleted queue
        $queue->forceDelete();

        return redirect()->route('home');



    }
}
