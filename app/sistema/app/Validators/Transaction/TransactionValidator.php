<?php

namespace App\Validators\Transaction;
use Validator;
use App\Exceptions\MyValidationException;
use App\Validators\AbstractValidator;

class TransactionValidator extends AbstractValidator {

	public function validate ($data, $rules) {

        $validator = Validator::make($data , $rules);

        if ($validator->fails()) {
            
           throw new MyValidationException("Erro ao validar os campos");           
        }        
    }

}
