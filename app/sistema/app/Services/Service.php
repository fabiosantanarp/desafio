<?php

namespace App\Services;

use Request;
use App\Exceptions\MyBusinessException;

class Service {    
    
    /**
     * Check index or keys in a array. 
     * Throw If exists required fields not in Request.
     * @param Array   $fields  Fields to be compared.     
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return Throw If exists required fields not in Request.
     * 
    */ 
    public function hasInArray(array $searchFields, array $array) {

        $fieldsNotFoundInRequest = [];

        foreach ($searchFields as $key => $field) {

            if (!array_key_exists($field, $array)) {

                array_push($fieldsNotFoundInRequest, $field);

            }
        }

        return $fieldsNotFoundInRequest;       

    }

}    