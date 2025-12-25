<?php

namespace App\Http\Controllers;

use App\Mail\ForcePasswordChangeEmail;
use App\Models\User;
use App\Mail\NewClientUserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientAdminController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->where('id_company', Auth::user()->id_company)->get();

        $data = [
            'subtitle' => 'Admistração de usuários',
            'users' => $users
        ];

        return view('client_admin.home', $data);
    }

    public function createUser()
    {
        $data = [
            'subtitle' => 'Criar usuário',

        ];

        return view('client_admin.create_user_frm', $data);
    }

    public function createUserSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:client-admin,client-user',
            ],
            [
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser um endereço de email válido.',
                'email.unique' => 'O email indicado já se encontra registrado.',

                'role.required' => 'O perfil é obrigatório.',
                'role.in' => 'O valor selecionado para o perfil é invalido.',
            ]
        );

        // create a new user
        $code = Str::random(64);

        // send email to the new user
        try {
            Mail::to($request->email)->send(new NewClientUserEmail($code, Auth()->user()->company->company_name));
        } catch (\Exception $e){
            return redirect()->back()->withInput()->with('server_error', 'Erro ao enviar o email. Por favor, tente novamente.');
        }

        // save user to the database
        $user = new User();
        $user->email = $request->email;
        $user->role = $request->role;
        $user->id_company = Auth()->user()->id_company;
        $user->code = $code;
        $user->code_expiration = now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION'));
        $user->save();

        return view('client_admin.create_user_success', [
            'subtitle' => 'Usuário criado!',
            'email' => $request->email
        ]);

    }

    public function forcePasswordReset($id)
    {
        if (!$user = $this->checkUserIsValid($this->decryptUserId($id))){
            return $this->redirectOnInvalidUser();
        }

        $data = [
            'subtitle' => 'Forçar alteração de senha',
            'user' => $user,
        ];

        return view('client_admin.force_password_change_confirm', $data);
    }

    public function forcePasswordResetConfirm($id)
    {
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // generate new code
        $code = Str::random(64);

        try{
            Mail::to($user->email)->send(new ForcePasswordChangeEmail($code));
        } catch(\Exception $e) {
            return redirect()->route('client.admin.home');
        }

        // update the user data in the database
        $user->password = null;
        $user->blocked_until = null;
        $user->deleted_at = null;
        $user->active = 1;
        $user->code = $code;
        $user->code_expiration = now()->addMinutes(config('constants.MAIL_NEW_CLIENT_CODE_EXPIRATION'));
        $user->save();

        return redirect()->route('client.admin.home');
    }

    public function deactivateUser($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // deactivate the user
        $user->active = 0;
        $user->save();

        return redirect()->route('client.admin.home');
    }

    public function activateUser($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // activate the user
        $user->active = 1;
        $user->save();

        return redirect()->route('client.admin.home');
    }

    public function blockUser($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        $data = [
            'subtitle' => 'Bloquear usuário',
            'user' => $user
        ];

        return view('client_admin.block_user_frm', $data);
    }

    public function blockUserSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'blocked_until' => 'required|date|after:now',
            ],
            [
                'blocked_until.required' => 'A data/hora deve ser selecionada.',
                'blocked_until.date' => 'A data/hora selecionada é inválida.',
                'blocked_until.after' => 'A data/hora selecionada deverá ser após a data/hora atual.',

            ]
        );

        // check if user_id exists
        if (empty($request->user_id)){
            return redirect()->route('client.admin.home');
        }

        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($request->user_id))) {
            return $this->redirectOnInvalidUser();
        }

        // block user
        $user->blocked_until = $request->blocked_until;
        $user->save();

        return redirect()->route('client.admin.home');

    }

    public function unblockUser($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // block user
        $user->blocked_until = null;
        $user->save();

        return redirect()->route('client.admin.home');
    }

    public function deleteUser($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // delete user (soft delete)
        $user->delete();

        return redirect()->route('client.admin.home');
    }

    public function restoreUser($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // restore user
        $user->restore();

        return redirect()->route('client.admin.home');
    }

    public function permDeleteClient($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // show the delete confirmation page
        $data = [
            'subtitle' => 'Eliminar Permanente',
            'user' => $user
        ];

        return view('client_admin.client_perm_delete', $data);
    }

    public function permDeleteClientConfirm($id)
    {
        // check if the user is valid
        if(!$user = $this->checkUserIsValid($this->decryptUserId($id))) {
            return $this->redirectOnInvalidUser();
        }

        // perm delete the company
        $user->forceDelete();

        return redirect()->route('client.admin.home');

    }

    public function editCompany()
    {
        $data = [
            'subtitle' => 'Editar empresa',
            'company' => Auth()->user()->company
        ];

        return view('client_admin.edit_company_frm', $data);
    }

    public function editCompanySubmit(Request $request)
    {
        dd($request->all());
    }

    private function decryptUserId($id)
    {
        // check if the id is valid
        try {
            return Crypt::decrypt($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function checkUserIsValid($id)
    {
        // check if the user with this id belongs to the same company of the authenticated user
        $user = User::withTrashed()
            ->where('id', $id)
            ->where('id_company', Auth()->user()->id_company)
            ->where('id', '!=', Auth()->user()->id)
            ->first();

        if (!$user){
            return null;
        }

        return $user;
    }

    private function redirectOnInvalidUser()
    {
        return redirect()->route('client.admin.home');
    }
}
