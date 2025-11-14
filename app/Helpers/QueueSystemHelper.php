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
