<?php

if (!function_exists('showValidationError')){
    function showValidationError($fieldName, $validationErrors){
        if ($validationErrors->has($fieldName)){
            return '<div class="text-sm italic text-red-500">'. $validationErrors->first($fieldName) .'</div>';
        } else {
            return '';
        }
    }
}

if (!function_exists('showServerError')){
    function showServerError(){
        if (session()->has('server_error')){
            return '<div class="text-sm italic text-red-500">'. session()->get('server_error') .'</div>';
        } else {
            return '';
        }
    }
}

if (!function_exists('getFormattedTicketNumber')){
    function getFormattedTicketNumber($ticketNumber, $prefix = null, $totalDigits = 3){
        $result = '';

        // prefix
        if ($prefix) {
            $result = $prefix;
        }

        // numbers
        if ($totalDigits > 0){
            $result .= str_pad($ticketNumber, $totalDigits, '0', STR_PAD_LEFT);
        }

        return $result;
    }
}

if (!function_exists('getTicketStateText')){
    function getTicketStateText($state)
    {
        $rules = [
            'waiting' => 'Aguardando',
            'called' => 'Atendido',
            'not_attended' => 'Não atendido',
            'dismissed' => 'Dispensado',
        ];

        return $rules[$state] ?? 'Desconhecido';
    }
}

if(!function_exists('getQueueStateIcon')) {
    function getQueueStateIcon($state)
    {
        $icons = [
            'active' => '<i class="fa-regular fa-circle-check text-green-700" title="Ativo"></i>',
            'inactive' => '<i class="fa-regular fa-circle-xmark text-red-700" title="Inativo"></i>',
            'done' => '<i class="fa-solid fa-ban text-slate-300" title="Concluído"></i>',
        ];

        return $icons[$state] ?? '-';
    }
}

if(!function_exists('getQueueStateText')) {
    function getQueueStateText($state)
    {
        $rules = [
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'done' => 'Concluido',
        ];

        return $rules[$state] ?? 'Desconhecido';
    }
}

if(!function_exists('getQueueStatePreview')) {
    function getQueuePreview($queue)
    {
        $previewHTML = '<div class="text-center">';
        $colors = json_decode($queue->queue_colors, true);

        // prefix
        if($queue->prefix !== '-'){
            $previewHTML .= '<spam style="padding: 2px 6px; background-color:' . $colors['prefix_bg_color'] . '; color:' . $colors['prefix_text_color'] .'">'. $queue->queue_prefix .'</spam>';
        }

        // ticket number

        $previewHTML .= '<span style="padding: 2px 6px; background-color:' . $colors['number_bg_color'] . '; color: ' . $colors['number_text_color'] . '">'. getFormattedTicketNumber(1, null, $queue->queue_total_digits) .'</span>';

        $previewHTML .= '</div>';

        return $previewHTML;
    }
}


