<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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

        // set the company logo
        if ($request->hasFile('company_logo')) {

            // create a unique file name
            $fileName = Str::uuid(). '.' . $request->company_logo->extension();
            $request->company_logo->storeAs('company_logos', $fileName, 'public');
            $company_logo = $fileName;

        } else {

            // without logo
            $company_logo = '_no_logo.png';

        }

        // create the company (client)
        $company = new Company();
        $company->company_name = $request->company_name;
        $company->company_logo = $company_logo;
        $company->uuid = Str::uuid();
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->status = $request->status;
        $company->save();

        // get the company_id
        $id_company = $company->id;

        // create the client-admin user
        $user = new User();
        $user->email = $request->admin_email;
        $user->id_company = $id_company;
        $user->role = 'client-admin';
        $user->code = $code;
        $user->code_expiration = now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION'));
        $user->active = $request->status === 'active' ? 1 : 0;
        $user->save();

        // display a success page
        $data = [
            'subtitle' => 'Sucesso',
            'company_name' => $request->company_name,
            'admin_email' => $request->admin_email
        ];

        return view('admin.create_company_success', $data);

    }

    public function companyDetails($id)
    {
        // check if the decrypted bundle ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }

        // get company details
        $company = Company::find($id);
        if(!$company) {
            return redirect()->route('admin.home');
        }

        $users = User::where('id_company', $id)->get();

        // get company queues with total tickets by status
        $queues = Queue::withTrashed()->where('id_company', $id)->withCount([

                'tickets as total_tickets' => function($query) {
                    $query->where('deleted_at', null);
                },

                'tickets as total_waiting' => function($query) {
                    $query->where('queue_ticket_status', 'waiting');
                },

                'tickets as total_called' => function($query) {
                    $query->where('queue_ticket_status', 'called');
                },

                'tickets as total_not_attended' => function($query) {
                    $query->where('queue_ticket_status', 'not_attended');
                },

                'tickets as total_dismissed' => function($query) {
                    $query->where('queue_ticket_status', 'dismissed');
                },

            ])->get();

        $data = [
            'subtitle' => 'Detalhes do cliente',
            'company' => $company,
            'users' => $users,
            'queues' => $queues,
        ];

        // display details view
         return view('admin.company_details', $data);

    }

    public function companyControlAccess($id)
    {
        // check if the id is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }


        // get company details
        $company = Company::find($id);
        if (!$company) {
            return redirect()->route('admin.home');
        }

        // return view
        $data = [
            'subtitle' => 'Controle de acesso',
            'company' => $company
        ];

        return view('admin.control_company_access', $data);
    }

    public function companyControlAccessSubmit(Request $request)
    {
        // check if the inputs are valid and updates the company status
        if(!$request->has('id') || !$request->has('action')) {
            return redirect()->route('admin.home');
        }

        // check if the id is valid
        try {
            $id = Crypt::decrypt($request->id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }

        $action = $request->action;

        // get the company details
        $company = Company::find($id);
        if(!$company) {
            return redirect()->route('admin.home');
        }

        // updates the company status
        if($action === 'disable') {
            $company->status = 'inactive';
            $company->save();
        } else if($action === 'enable') {
            $company->status = 'active';
            $company->save();
        }

        return redirect()->route('admin.home');
    }

    public function deleteCompany($id)
    {
        // check if the id is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }

        // get the company details
        $company = Company::find($id);
        if(!$company) {
            return redirect()->route('admin.home');
        }

        $data = [
            'subtitle' => 'Excluir cliente',
            'company' => $company
        ];

        return view('admin.delete_company_confirm', $data);
    }

    public function deleteCompanyConfirm($id)
    {
        // check if the id is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }

        // get the company details
        $company = Company::find($id);
        if(!$company) {
            return redirect()->route('admin.home');
        }

        // delete the queue
        $company->delete();

        return redirect()->route('admin.home');
    }

    public function restoreCompany($id)
    {
        // check if the id is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }

        // check if the queue exists and belongs to the authenticated user's company
        $company = Company::withTrashed($id);
        if(!$company){
            return redirect()->route('admin.home');
        }

        // restore the soft deleted company
        $company->restore();

        return redirect()->route('admin.home');
    }

    public function permCompanyQueue($id)
    {
        // check if the id is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }

        // get the company details
        $company = Company::withTrashed()->find($id);
        if(!$company) {
            return redirect()->route('admin.home');
        }

        // show the delete confirmation page
        $data = [
            'subtitle' => 'Eliminar Permanente',
            'company' => $company
        ];

        return view('admin.company_perm_delete', $data);
    }

    public function permDeleteCompanyConfirm($id)
    {
        // check if the id is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('admin.home');
        }

        // get the company details
        $company = Company::withTrashed()->find($id);
        if(!$company) {
            return redirect()->route('admin.home');
        }

        // perm delete the company
        $company->forceDelete();

        return redirect()->route('admin.home');

    }

}
