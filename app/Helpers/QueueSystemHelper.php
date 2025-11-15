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

