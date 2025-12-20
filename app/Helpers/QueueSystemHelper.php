<?php

use Illuminate\Support\Facades\Storage;

if(!function_exists('showValidationError')){
    function showValidationError($fieldName, $validationErrors)
    {
        if($validationErrors->has($fieldName)){
            return '<div class="text-sm italic text-red-500">' . $validationErrors->first($fieldName) . '</div>';
        } else {
            return '';
        }
    }
}

if(!function_exists('showServerError')){
    function showServerError()
    {
        if(session()->has('server_error')){
            return '<div class="text-sm italic text-red-500">' . session()->get('server_error') . '</div>';
        } else {
            return '';
        }
    }
}

if(!function_exists('getFormattedTicketNumber')){
    function getFormattedTicketNumber($ticketNumber, $prefix = null, $totalDigits = 3)
    {
        $result = '';

        // prefix
        if($prefix) {
            $result = $prefix;
        }

        // numbers
        if($totalDigits > 0) {
            $result .= str_pad($ticketNumber, $totalDigits, '0', STR_PAD_LEFT);
        }

        return $result;
    }
}

if(!function_exists('getTicketStateText')) {
    function getTicketStateText($state) {

        $rules = [
            'waiting' => 'Aguardando',
            'called' => 'Atendido',
            'not_attended' => 'Não atendido',
            'dismissed' => 'Dispensado'
        ];

        return $rules[$state] ?? 'Desconhecido';
    }
}

if(!function_exists('getQueueStateIcon')) {
    function getQueueStateIcon($state) {

        $icons = [
            'active' => '<i class="fa-regular fa-circle-check text-green-700" title="Ativa"></i>',
            'inactive' => '<i class="fa-regular fa-circle-xmark text-red-700" title="Inativa"></i>',
            'done' => '<i class="fa-solid fa-ban text-slate-300" title="Concluída"></i>',
        ];

        return $icons[$state] ?? '-';
    }
}

if(!function_exists('getQueueStateText')) {
    function getQueueStateText($state) {

        $rules = [
            'active' => 'Ativa',
            'inactive' => 'Inativa',
            'done' => 'Terminada',
        ];

        return $rules[$state] ?? 'Desconhecido';
    }
}

if(!function_exists('getQueuePreview')) {
    function getQueuePreview($queue)
    {
        $previewHTML = '<div class="text-center">';
        $colors = json_decode($queue->queue_colors, true);

        // prefix
        if($queue->queue_prefix !== '-') {
            $previewHTML .= '<span style="padding: 2px 6px; background-color:' . $colors['prefix_bg_color'] . '; color:' . $colors['prefix_text_color'] . '">' . $queue->queue_prefix . '</span>';
        }

        // ticket number
        $previewHTML .= '<span style="padding: 2px 6px; background-color:' . $colors['number_bg_color'] . '; color:' . $colors['number_text_color'] . '">' . getFormattedTicketNumber(1, null, $queue->queue_total_digits) . '</span>';
        $previewHTML .= '</div>';

        return $previewHTML;
    }
}

if (!function_exists('getClientStatusIcon')){
    function getClientStatusIcon($client)
    {
        $icons = [
            'active' => '<i class="fa-regular fa-circle-check text-green-700" title="Ativa"></i>',
            'inactive' => '<i class="fa-regular fa-circle-xmark text-red-700" title="Inativa"></i>',
        ];

        if ($client->deleted_at || $client->status === 'inactive'){
            return $icons['inactive'];
        } else {
            return $icons['active'];
        }
    }
}

if (!function_exists('getCompanyLogo')){
    function getCompanyLogo($logo)
    {
        // check if the company logo exists inside the storage/app/public/company_logos
        if ($logo && Storage::disk('public')->exists('company_logos/' . $logo)){
            return asset('storage/company_logos/' . $logo);
        } else {
            return asset('storage/company_logos/_no_logo.png');
        }
    }
}

if (!function_exists('getUserStatus')){
    function getUserStatus($user)
    {
        if ($user->password === null || $user->active === 0 || $user->blocked_until > now()){
            return '<i class="fa-regular fa-circle-xmark text-red-700 me-2" title="Inativo"></i> Inativo';
        } else {
            return '<i class="fa-regular fa-circle-check text-green-700 me-2" title="Ativo"></i> Ativo';
        }

    }
}

if (!function_exists('getUserRole')){
    function getUserRole($role)
    {
        $roles = [
            'sys-admin' => '<i class="fa-solid fa-user-gear me-2" title="Admistrador do sistema"></i>Admistrador do sistema',
            'client-admin' => '<i class="fa-solid fa-user-shield me-2" title="Admistrador do cliente"></i>Admistrador do cliente',
            'client-user' => '<i class="fa-solid fa-user me-2" title="Usuário"></i>Usuário',
        ];

        return $roles[$role] ?? 'Desconhecido';

    }
}


