<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // testar a conexão com a base de dados
    try {
        DB::connection()->getPdo();
        echo "Conexão com o banco de dados OK!";
    } catch (Exception $e) {
        echo "Não foi possivel conectar com o banco de dados! Erro: " . $e->getMessage();
    }

});
