<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Queue;
use App\Models\QueueTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class QueuesDisplayController extends Controller
{
    public function index()
    {
        // apresentar a tela de exibição das filas de espera
        $data = [
            'subtitle' => 'Apresentador de filas',
            'credential' => session()->get('queues_display_credential')
        ];

        return view('ticket_display.display', $data);

    }

    public function credentials()
    {
        // apresentar a tela de inserção das credenciais do bundle
        $data = [
            'subtitle' => 'Apresentador de filas',
        ];

        return view('ticket_display.credential_frm', $data);
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
        session()->put('queues_display_credential', $request->credential_username);

        // redirect to the queues display
        return redirect()->route('queues.display');

    }

    public function getBundleData(Request $request)
    {
//        return response()->json([
//            'status', 'success',
//            'code' => 200,
//            'message' => 'Request is working!'
//        ]);

        if ($request->has('credential')){
            try {
                $credential = Crypt::decrypt($request->credential);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid credential format'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Credential is required'
            ]);
        }

        // prepare json structure with all the info about the bundle
        $bundle = Bundle::where('credential_username', $credential)->first();

        if (!$bundle){
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Bundle not found'
            ]);
        }

        // get all queues and the first ticket with status = 'called' from each queue
        $queues = Queue::whereIn('hash_code', json_decode($bundle->queues))
            ->where('deleted_at', null)
            ->with(['tickets' => function ($query){
                $query->where('queue_ticket_status', 'called')
                    ->orderBy('queue_ticket_created_at', 'desc')
                    ->limit(1);
            }])->get();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'success',
            'data' => [
                'bundle' => $bundle,
                'queues' => $queues,
            ]
        ]);


    }


}
