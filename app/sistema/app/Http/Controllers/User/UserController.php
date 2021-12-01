<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Response\ApiMessageResponse;
use App\Response\EnumMessageResponseType;
use Illuminate\Http\Request;

use App\Exceptions\MyException;
use Throwable;

class UserController extends Controller
{

    public function __construct(UserService $service) {

        $this->service = $service;

    }

    /**
     * Create a new user (person or company)             
     *      
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        try {

            $input = sanitizeData($request->all()); 
            
            $newUserInfo = $this->service->store($input);

            $messageResponse = json_encode(new ApiMessageResponse(true, EnumMessageResponseType::Success, "Cadastrado com sucesso", $newUserInfo, null));

            return response($messageResponse, 200);

        } catch (Throwable $e) {

            throw new MyException($e->getMessage(), $e->getCode(), $e, null);

        }
        
    }   

/**
     * Get Total Balance of a user (person or company)             
     *      
     * @param  int $idUser  User's Id.
     * @return \Illuminate\Http\Response
     */
    public function getTotalBalance(Request $request) {

        try {

            $input = sanitizeData($request->all());
           
            $data["totalBalance"] = $this->service->getTotalBalance($input);

            $messageResponse = json_encode(new ApiMessageResponse(true, EnumMessageResponseType::Success, null, $data, null));

            return response($messageResponse, 200);

        } catch (Throwable $e) {

            throw new MyException($e->getMessage(), $e->getCode(), $e, null);

        }
        
    }       

}
