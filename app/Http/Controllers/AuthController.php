<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
                'password.regex' => 'A Senha Deve conter entre 6 e 16 caracteres, ter uma letra maiúscula, uma minúscula e um algarismo.',
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
            //login successful
            auth()->login($user);
            dd(auth()->user());
            // redirect to home page
            return redirect()->route('home');

        } else {
            //login failed
            die('Login inválido');
        }

    }

    public function logout()
    {
        // logout de usuário autenticado
    }
}
