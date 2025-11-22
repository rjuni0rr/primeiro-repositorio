<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login_frm');
    }

    public function loginSubmit(Request $request)
    {
        // form validation
        $request->validate(
            // rules for validation
            [
                'username' => 'required|email',
                'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,16}$/'
            ],
            // error messages
            [
                'username.required' => 'O usuário é obrigatório',
                'username.email' => 'O usuário deve ter um e-mail válido',
                'password.required' => 'A Senha é obrigatória',
                'password.regex' => 'A Senha deve conter entre 6 e 16 caracteres, ter uma letra maiúscula, uma minúscula e um algarismo.',
            ]
        );

        // user authenticator
        $user = User::where('email', trim($request->username))
            ->where('active', true)
            ->whereNull('deleted_at')
            ->where(function ($query){
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '<', now());
            })
            ->first();

        // check if user exists and password matches
        if($user && Hash::check(trim($request->password), $user->password)){
            //login user
            $this->loginUser($user);

            // redirect to home page
            return redirect()->route('home');


        } else {
            //login failed
            return redirect()
                ->back()
                ->withInput()
                ->with('server_error', 'Login inválido.');
        }

    }

    private function loginUser($user)
    {
        // update last login and resets other fields
        $user->last_login = now();
        $user->code = null;
        $user->code_expiration = null;
        $user->blocked_until = null;
        $user->save();

        // place user in session
        auth()->login($user);

    }


    public function logout()
    {
        // logout de usuário autenticado
        auth()->logout();

        // invalidate session - clear all session data
        session()->invalidate();

        // regenerate session token
        session()->regenerateToken();

        return redirect()->route('login');

    }

    public function changePassword()
    {
        return view('auth.change_password_frm', ['subtitle' => 'Alterar Senha']);

    }

    public function changePasswordSubmit(Request $request)
    {
        // enviar formulário
        $request->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,16}$/|confirmed'
            ],
            [
                'current_password' => 'A senha atual é obritatória.',
                'new_password.required' => 'A nova senha é obrigatória.',
                'new_password.regex' => 'A nova senha deve conter entre 6 e 16 caracteres, ter uma letra maiúscula, uma minúscula e um algarismo.',
                'new_password.confirmed' => 'As novas senhas não se coincidem.'
            ]
        );
        // get authenticated user
        $user = auth()->user();

        // check if current password matches
        if (Hash::check($request->current_password, $user->password)){

            // update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('home')->with('message',  'Senha alterada com sucesso!');

        } else {
            return redirect()->back()->with('server_error', 'Senha atual inválida.');
        }
    }


}

