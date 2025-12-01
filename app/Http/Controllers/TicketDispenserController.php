<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketDispenserController extends Controller
{
    public function index()
    {
        echo 'Dispensador de tickets';
    }

    public function credentials()
    {
        echo 'Formulário de credenciais do bundle';

    }

    public function credentialsSubmit(Request $request)
    {
        // credentials form submit
        // validate and process the request
        // return response or redirect

    }
}
