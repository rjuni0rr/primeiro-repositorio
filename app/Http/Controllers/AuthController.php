<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function changePassword()
    {
        return view('auth.change_password_frm', ['subtitle' => 'Alterar senha']);
    }

    public function changePasswordSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,16}$/|confirmed'
            ],
            [
                'current_password' => 'A senha atual é obrigatória.',
                'new_password.required' => 'A nova senha é obrigatória.',
                'new_password.regex' => 'A nova senha deve conter entre 6 e 16 caracteres, ter uma maiúscula, uma minúscula e um algarismo.',
                'new_password.confirmed' => 'A nova senha e a repetição não estão iguais.',
            ]
        );

        // get authenticated user
        $user = auth()->user();

        // check if current password matches
        if(Hash::check($request->current_password, $user->password)){

            // update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('home')->with('message', 'Senha alterada com sucesso');

        } else {

            return redirect()->back()->with('server_error', 'Senha atual inválida.');

        }
    }

    public function concludeRegistration($code)
    {

        echo "Conclusão do registro<br>";
        echo $code;

    }
}
