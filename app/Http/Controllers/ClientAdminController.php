<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\NewClientUserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientAdminController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->where('id_company', Auth::user()->id_company)->get();

        $data = [
            'subtitle' => 'Admistração de usuários',
            'users' => $users
        ];

        return view('client_admin.home', $data);
    }

    public function createUser()
    {
        $data = [
            'subtitle' => 'Criar usuário',

        ];

        return view('client_admin.create_user_frm', $data);
    }

    public function createUserSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:client-admin,client-user',
            ],
            [
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser um endereço de email válido.',
                'email.unique' => 'O email indicado já se encontra registrado.',

                'role.required' => 'O perfil é obrigatório.',
                'role.in' => 'O valor selecionado para o perfil é invalido.',
            ]
        );

        // create a new user
        $code = Str::random(64);

        // send email to the new user
        try {
            Mail::to($request->email)->send(new NewClientUserEmail($code, Auth()->user()->company->company_name));
        } catch (\Exception $e){
            return redirect()->back()->withInput()->with('server_error', 'Erro ao enviar o email. Por favor, tente novamente.');
        }

        // save user to the database
        $user = new User();
        $user->email = $request->email;
        $user->role = $request->role;
        $user->id_company = Auth()->user()->id_company;
        $user->code = $code;
        $user->code_expiration = now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION'));
        $user->save();

        return view('client_admin.create_user_success', [
            'subtitle' => 'Usuário criado!',
            'email' => $request->email
        ]);

    }
}
