<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BundlesController extends Controller
{
    public function index()
    {
        $data = [
            'subtitle' => 'Bundles',
            'bundles' => auth()->user()->company->bundles()->get()
        ];

        return view('bundles.home', $data);
    }

    public function createBundle()
    {
        $data = [
            'subtitle' => 'Criar bundle',
            'queues' => auth()->user()->company->queues()->get(),

        ];

        return view('bundles.create_bundle_frm', $data);
    }
}
