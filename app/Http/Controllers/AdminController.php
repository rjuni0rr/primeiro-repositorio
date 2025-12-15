<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
            'subtitle' => 'Administração',
            'clients' => $this->getClientsList()
        ];

//        $data['clients'] = collect();

        return view('admin.home', $data);



    }

    private function getClientsList()
    {
        // return the clints list (all companies)
        return Company::withTrashed()->withCount('users')->get();
    }
}
