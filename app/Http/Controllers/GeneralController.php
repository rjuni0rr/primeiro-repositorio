<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function termsAndConditions()
    {
        $data = [
            'subtitle' => 'Termos e condições'
        ];

        return view('general.terms_and_conditions', $data);
    }

    public function changelog()
    {
        $data = [
            'subtitle' => 'Changelog',
            'changelog' => config('changelog'),
        ];

        return view('changelog.changelog', $data);
    }
}
