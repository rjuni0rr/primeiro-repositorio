<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QueuesDisplayController extends Controller
{
    public function index()
    {
        // apresentar a tela de exibição das filas de espera
        echo "Exibir filas de espera";
    }

    public function credentials()
    {
        // apresentar a tela de inserção das credenciais do bundle
        echo "Formulário de credenciais";
    }

    public function credentialsSubmit(Request $request)
    {
        // submissão do formuçário de credenciais
        echo "submissão de credenciais";

    }

    public function getBundleData(Request $request)
    {
        // obter dados dobundle, respectivas filas de espera e tickets | pedido assíncrono via POST
        echo "Dados do bundle";
    }


}
