<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TicketDispenserController extends Controller
{
    public function index()
    {
        $data = [
            'subtitle' => 'Dispensador',
        ];

        return view('ticket_dispenser.dispenser', $data);
    }

    public function credentials()
    {
        $data = [
            'subtitle' => 'Dispensador',
        ];

        return view('ticket_dispenser.credential_frm', $data);
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
        $result = Bundle::where('credential_username', $request->credential_username)->first();

        if (!$result){
            return redirect()->back()->withInput()->with(['server_error' => 'Login inválido']);
        }

        // check if the password matches
//        if (!Hash::check($request->credential_password, $result->credential_password)){
//            return redirect()->back()->withInput()->with(['server_error' => 'Login inválido']);
//        }

        // store in session
        session()->put('ticket_dispenser_credential', $result->credential_username);

        // redirect to the ticket dispenser
        return redirect()->route('dispenser');

    }
}
