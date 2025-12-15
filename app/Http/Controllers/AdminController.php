<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\NewClientAdminEmail;

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

    public function createCompany()
    {
        $data = [
            'subtitle' => 'Novo cliente'
        ];

        return view('admin.create_company_frm', $data);
    }

    public function createCompanySubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'company_logo' => 'image|mimes:jpeg,png|dimensions:width=200,height=200',
                'company_name' => 'required|max:100|unique:companies,company_name',
                'address' => 'required|max:255',
                'phone' => 'required|max:20',
                'email' => 'required|email|max:100',
                'status' => 'required|in:active,inactive',
                'admin_email' => 'required|email|max:50|unique:users,email',
            ],
            [
                'company_logo.image' => 'O arquivo enviado não é uma imagem válida.',
                'company_logo.mimes' => 'A imagem deve estar no formato JPEG ou PNG.',
                'company_logo.dimensions' => 'A imagem deve ter exatamente 200x200 pixels.',
                'company_name.required' => 'O nome da empresa é obrigatório.',
                'company_name.max' => 'O nome da empresa não pode exceder 100 caracteres.',
                'company_name.unique' => 'Já existe uma empresa com esse nome.',
                'address.required' => 'O endereço é obrigatório.',
                'address.max' => 'O endereço não pode exceder 255 caracteres.',
                'phone.required' => 'O telefone é obrigatório.',
                'phone.max' => 'O telefone não pode exceder 20 caracteres.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser um endereço de email válido.',
                'email.max' => 'O email não pode exceder 100 caracteres.',
                'status.required' => 'O status é obrigatório.',
                'status.in' => 'O status selecionado é inválido.',
                'admin_email.required' => 'O email do administrador é obrigatório.',
                'admin_email.email' => 'O email do administrador deve ser um endereço de email válido.',
                'admin_email.max' => 'O email do administrador não pode exceder 50 caracteres.',
                'admin_email.unique' => 'Já existe um usuário com esse email.'
            ]
        );

        // enviar um email de teste
//        try {
//            Mail::raw('Email de teste (corpo do email).', function ($message){
//                $message->to('admin1@teste.com')->subject('Email de teste (subject)');
//            });
//
//            echo "Sucesso!";
//        } catch (\Exception $e) {
//            echo "Erro ao enviar o email: " . $e->getMessage();
//        }

        $code = Str::random(64);
        try {
            Mail::to($request->admin_email)->send(new NewClientAdminEmail($code, $request->company_name));

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('server_error', 'Erro ao enviar o email. Por favor, tente novamente');
        }
    }
}
