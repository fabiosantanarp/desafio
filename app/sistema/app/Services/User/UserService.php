<?php

namespace App\Services\User;
use App\Services\Service;
use App\Validators\User\UserValidator;
use App\DataAccess\User\UserDataAccess;

use App\Exceptions\MyBusinessException;

class UserService extends Service {

    public function __construct(UserValidator $validator, UserDataAccess $dataaccess) {

        $this->validator = $validator;        
        $this->dataaccess = $dataaccess;

    }

    /**
    * Get total balance of a user.     
    * @param Array   $data  User information     
    * @author Fábio Sant'Ana <fabio@4comtec.com.br>
    * @return Int Total Balance of specific user.
    * 
    */ 
    public function getTotalBalance(array $data) { 

        // Required fields.
        $requiredFields  = ['idUser'];

        //Check required fields in data.
        $notFoundFields = $this->hasInArray($requiredFields, $data);

        if (count($notFoundFields) > 0) {              

            throw new MyBusinessException("Dados nao encontrados (" . implode(", ", $notFoundFields) . "). Verifique a documentacao.");

        }        
        
        return $this->dataaccess->getTotalBalanceUser($data["idUser"]);

    }    

    /**
    * Method for storing a new user.     
    * @param Array   $data  User information     
    * @author Fábio Sant'Ana <fabio@4comtec.com.br>
    * 
    */ 
    public function store(array $data) {  

        if ($data["typeUser"] == "person") {

            return  $this->__storePerson($data);

        } else if ($data["typeUser"] == "company"){

            return $this->__storeCompany($data);

        } else {

            throw new MyBusinessException("Tipo de Usuário (typeUser) inválido");

        }

    }

    /**
    * Private method for storing person.
    * @param Array   $data  User information     
    * @author Fábio Sant'Ana <fabio@4comtec.com.br>
    * 
    */ 
    private function __storePerson(array $data) {

        // Required fields.
        $requiredFields  = ['typeUser', 'email', 'password', 'cpf', 'firstName', 'lastName'];

        //Check required fields in data.
        $notFoundFields = $this->hasInArray($requiredFields, $data);

        if (count($notFoundFields) > 0) {              

            throw new MyBusinessException("Dados nao encontrados (" . implode(", ", $notFoundFields) . "). Verifique a documentacao.");

        }
        
        // determine specific rule for validation data
        $rules = [
            'typeUser' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'cpf' => 'required|string|min:14|max:14',
            'firstName'=> 'required|string',
            'lastName' => 'required|string'
        ];

        // call validator method, passing data and rules.
        $this->validator->validate($data, $rules); //Validator

        // insert
        $userId = $this->dataaccess->addUser($data); // Insert

        // insert new user userId to the data pre-existent.
        $data["userId"] = $userId;

        return $data;

    }

    /**
    * Private method for storing company.
    * @param Array   $data  Company information     
    * @author Fábio Sant'Ana <fabio@4comtec.com.br>
    * 
    */ 
    private function __storeCompany(array $data) {

        // Required fields.
        $requiredFields = ['typeUser', 'email', 'password', 'cnpj', 'corporateName'];
        
        //Check required fields in data.
        $notFoundFields = $this->hasInArray($requiredFields, $data);

        if (count($notFoundFields) > 0) {              

            throw new MyBusinessException("Dados nao encontrados (" . implode(", ", $notFoundFields) . "). Verifique a documentacao.");

        }
      
        // determine specific rule for validation data
        $rules = [
            'typeUser' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'cnpj' => 'required|string|min:18|max:18',
            'corporateName'=> 'required|string',
        ];

        // call validator method, passing data and rules.
        $this->validator->validate($data, $rules); //Validator

        // insert
        $userId = $this->dataaccess->addUser($data); // Insert

        // insert new user userId to the data pre-existent.
        $data["userId"] = $userId;

        return $data;

    }

   


}    