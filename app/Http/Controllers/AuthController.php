<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                'username.required' => 'O usuário é obrigatório.',
                'username.email' => 'O usuário deve ser um e-mail válido.',
                'password.required' => 'A senha é obrigatória.',
                'password.regex' => 'A senha deve conter entre 6 e 16 caracteres, ter uma maiúscula, uma minúscula e um algarismo.'
            ]
        );

        // user authentication
        $user = User::where('email', trim($request->username))
            ->where('active', true)
            ->whereNull('deleted_at')
            ->where(function($query){
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '<', now());
            })
            ->first();

        // check if user exists and password matches
        if($user && Hash::check(trim($request->password), $user->password)){

            // check if user belongs to an active company (except admin)
            if ($user->role !== 'sys-admin' && ($user->company->deleted_at || $user->company->status != 'active')){
                return redirect()->back()->withInput()->with('server_error', 'Login inválido.');
            }


            // login user
            $this->loginUser($user);

            // redirect to home page or admin page if the user is admin
            if ($user->role === 'sys-admin'){
                return redirect()->route('admin.home');
            } else {
                return redirect()->route('home');
            }

        } else {

            // login failed
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
        // logout
        auth()->logout();

        // invalidate session - clear all session data
        session()->invalidate();

        // regenerate session token
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
