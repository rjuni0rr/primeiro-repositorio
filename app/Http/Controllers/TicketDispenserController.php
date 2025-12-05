<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TicketDispenserController extends Controller
{
    public function index()
    {
        $data = [
            'subtitle' => 'Dispensador',
            'credential' => session()->get('ticket_dispenser_credential')
        ];
        return view('ticket_dispenser.dispenser', $data);
    }

    public function credentials()
    {
        return view('ticket_dispenser.credential_frm');
    }

    public function credentialsSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'credential_username' => 'required|size:64',
                'credential_password' => 'required|size:64',
            ],
            [
                'credential_username.required' => 'A credencial username é obrigatória.',
                'credential_username.size' => 'A credencial username deve ter 64 caracteres.',
                'credential_password.required' => 'A credencial password é obrigatória.',
                'credential_password.size' => 'A credencial password deve ter 64 caracteres.',
            ]
        );

        // check if the credentials are valid
        $result = Bundle::where('credential_username', $request->credential_username)
            ->first();

        if(!$result) {
            return redirect()
                ->back()
                ->withInput()
                ->with(['server_error' => 'Credenciais inválidas.']);
        }

        // check if the password matches
        if(!Hash::check($request->credential_password, $result->credential_password)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(['server_error' => 'Credenciais inválidas.']);
        }

        // store in session
        session()->put('ticket_dispenser_credential', $request->credential_username);

        // redirect to the ticket dispenser
        return redirect()->route('dispenser');
    }

    public function getBundleData($credential)
    {
        // preapare json structure with all the info about the bundle
        $bundle = Bundle::where('credential_username', $credential)->first();

        if (!$bundle){
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Bundle not found'
            ]);
        }

        // get all queues information from the bundle
        $queues = Queue::whereIn('hash_code', json_decode($bundle->queues))->where('status', 'active')->where('deleted_at', null)->get();

        if ($queues->isEmpty()){
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'No active queues found for this bundle.'
            ]);
        }

        // prepare the data to be returned
        return response()->json(
                [
                'status' => 'success',
                'code' => 200,
                'message' => 'Success',
                'queues' => $queues->map(function ($queue){
                    return [
                        'id' => $queue->id,
                        'name' => $queue->name,
                        'description' => $queue->description,
                        'service' => $queue->service_name,
                        'desk' => $queue->service_desk,
                        'prefix' => $queue->queue_prefix,
                        'digits' => $queue->queue_total_digits,
                        'colors' => json_decode($queue->queue_colors, true)
                    ];
                }),
            ],
            200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_UNICODE
        );
    }
}
