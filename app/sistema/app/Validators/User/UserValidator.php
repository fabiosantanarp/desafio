<?php

namespace App\Validators\User;
use Validator;
use App\Exceptions\MyValidationException;
use App\Validators\AbstractValidator;

class UserValidator extends AbstractValidator {

	public function validate ($data, $rules) {

        $validator = Validator::make($data , $rules);

        if ($validator->fails()) {
            
           throw new MyValidationException("Erro ao validar os campos");           
        }        
    }

}
