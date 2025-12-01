<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\Bundle;

class BundlesController extends Controller
{
    public function index()
    {
        $data = [
            'subtitle' => 'Bundles',
            'bundles' => auth()->user()->company->bundles()->withTrashed()->get()
        ];

        return view('bundles.home', $data);
    }

    public function createBundle()
    {
        $data = [
            'subtitle' => 'Criar bundle',
            'queues' => auth()->user()->company->queues()->get(),

        ];

        return view('bundles.bundle_create_frm', $data);
    }

    public function createBundleSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'bundle_name' => 'required|string|min:5|max:100',
                'credential_username' => 'required|string|size:64',
                'credential_password' => 'required|string|size:64',
            ],
            [
                'bundle_name.required' => 'O nome do bundle é obrigatório',
                'bundle_name.string' => 'O nome do bundle deve ser uma string.',
                'bundle_name.min' => 'O nome do bundle deve ter pelo menos 5 caracteres.',
                'bundle_name.max' => 'O nome do bundle deve ter no máximo 100 caracteres.',

                'credential_username.required' => 'A credencial username é obrigatória.',
                'credential_username.string' => 'A credencial username deve ser uma string.',
                'credential_username.size' => 'A credencial deve ter 64 caracteres.',

                'credential_password.required' => 'A credencial password é obrigatória.',
                'credential_password.string' => 'A credencial password deve ser uma string.',
                'credential_password.size' => 'A credencial deve ter 64 caracteres.',
            ]
        );

        // check if the queue list is a valid json and the json isn't empty
        if (empty($request->queues_list) || json_decode($request->queues_list) == null || empty(json_decode($request->queues_list))){
            return redirect()->back()->withInput()->withErrors(['queues_list' => 'A lista de filas é obrigatória.']);
        }

        // check if the name of the bundle already exists
        $bundle_name = $request->bundle_name;
        $bundleExists = auth()->user()->company->bundles()->where('name', $bundle_name)->exists();
        if ($bundleExists) {
            return redirect()->back()->withInput()->withErrors(['bundle_name' => 'Já existe um bundle com esse nome.']);
        }

        // check if the queues in the queues_list exists and belongs to the user's company
        $queues_list = json_decode($request->queues_list, true);
        $queues_hash_codes = array_map(function ($queue){
            return $queue['hash_code'];
        }, $queues_list);

        $valid_queues = auth()->user()->company->queues()->whereIn('hash_code', $queues_hash_codes)->pluck('hash_code')->toArray();

        if (count($valid_queues) !== count($queues_hash_codes)) {
            return redirect()
            ->back()
            ->withInput()
            ->withErrors(['queues_list' => 'Algumas filas selecionadas não existem ou não pertencem à sua empresa.']);
        }

        // create the new bundle
        $newBundle = new Bundle();
        $newBundle->id_company = auth()->user()->company->id;
        $newBundle->name = $bundle_name;
        $newBundle->queues = json_encode($valid_queues);
        $newBundle->credential_username = $request->credential_username;
        $newBundle->credential_password = bcrypt($request->credential_password);
        $newBundle->save();

        return redirect()->route('bundles.home');
    }

    public function generateCredentialValue($num_chars)
    {
//        return response()->json(['hash' => 'abc123']);

        // validate the request
        if (!is_numeric($num_chars) || $num_chars <= 0 || $num_chars > 64) {
            return response()->json(['error' => 'Invalid number of characters'], 400);
        }

        // generate random string to create the hash code
        $credentialValue = Str::random($num_chars);

        while (Bundle::where('credential_username', $credentialValue)->exists()){
            $credentialValue = Str::random($num_chars);
        }

        return response()->json(['hash' => $credentialValue]);

    }

    public function edit($id)
    {
        // check if the decrypted bundle ID is valid
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e){
            abort(403, 'ID de bundle inválido.');
        }

        // find the bundle by id
        $bundle = Bundle::find($id);
        if (!$bundle || $bundle->id_company !== auth()->user()->company->id){
            return redirect()->route('bundles.home');
        }

        $data = [
            'subtitle' => 'Editar bundle',
            'bundle' => $bundle,
            'bundle_queue_list' => $this->getBundleQueueList($id),
            'queues' => auth()->user()->company->queues()->get()
        ];

        return view('bundles.bundle_edit_frm', $data);
    }

    public function editSubmit(Request $request)
    {
        // form validation
        $request->validate(
            [
                'bundle_name' => 'required|string|min:5|max:100',
                'queues_list' => 'required',
            ],
            [
                'bundle_name.required' => 'O nome do bundle é obrigatório',
                'bundle_name.string' => 'O nome do bundle deve ser uma string.',
                'bundle_name.min' => 'O nome do bundle deve ter pelo menos 5 caracteres.',
                'bundle_name.max' => 'O nome do bundle deve ter no máximo 100 caracteres.',

                'queues_list.required' => 'A lista de filas é obrigatória.',
            ]
        );

        // check if bundle_id is valid
        if (empty($request->bundle_id)){
            return redirect()->route('bundles.home');
        }

        // tries to decrypt the bundle_id
        try {
            $bundle_id = Crypt::decrypt($request->bundle_id);
        } catch (\Exception $e){
            abort(403, 'ID de bundle inválido.');
        }

        // check if the queue list is a valid json and the json isn't empty
        if (empty($request->queues_list) || json_decode($request->queues_list) == null || empty(json_decode($request->queues_list))){
            return redirect()->back()->withInput()->withErrors(['queues_list' => 'A lista de filas é obrigatória.']);
        }

        // check if the name of the bundle already exists
        $bundle_name = $request->bundle_name;
        $bundleExists = auth()->user()->company->bundles()->where('name', $bundle_name)->where('id', '!=', $bundle_id)->exists();
        if ($bundleExists) {
            return redirect()->back()->withInput()->withErrors(['bundle_name' => 'Já existe um bundle com esse nome.']);
        }

        // check if the queues in the queues_list exists and belongs to the user's company
//        $queues_list = json_decode($request->queues_list, true);
//        $queues_hash_codes = array_map(function ($queue){
//            return $queue['hash_code'];
//        }, $queues_list);
//
//        $valid_queues = auth()->user()->company->queues()->whereIn('hash_code', $queues_hash_codes)->pluck('hash_code')->toArray();
//
//        if (count($valid_queues) !== count($queues_hash_codes)) {
//            return redirect()
//                ->back()
//                ->withInput()
//                ->withErrors(['queues_list' => 'Algumas filas selecionadas não existem ou não pertencem à sua empresa.']);
//        }


        dd($request->all());
    }

    private function getBundleQueueList($id)
    {
        $queues = json_decode(Bundle::find($id)->queues);
        $companyQueues = Queue::where('id_company', auth()->user()->company->id)
            ->whereIn('hash_code', $queues)->get();

        $queueList = [];
        foreach ($companyQueues as $queue){
            $queueList = [
                'name' => $queue->name,
                'hash_code' => $queue->hash_code
            ];
        }

        return $queueList;
    }

    public function delete($id)
    {
        // tries to decrypt the bundle_id
        try {
            $bundle_id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return redirect()->route('bundles.home');
        }

        // get the bundle and check if it belongs to the user's company
        $bundle = Bundle::find($id);
        if (!$bundle || $bundle->id_company != auth()->user()->company->id){
            return redirect()->route('home');
        }

        // prepare data to the delete confirmation view
        $data = [
            'subtitle' => 'Eliminar bundle',
            'bundle' => $bundle
        ];

        return view('bundles.bundle_delete_confirm', $data);
    }

    public function deleteConfirm($id)
    {

    }

    public function restore($id)
    {

    }
}
