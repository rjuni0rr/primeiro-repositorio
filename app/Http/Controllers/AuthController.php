<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        echo "Formulário de login";
    }

    public function loginSubmit(Request $request)
    {
        // trattamento de formulário do login
    }

    public function logout()
    {
        // logout de usuário autenticado
    }
}
