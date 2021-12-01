<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Response\ApiMessageResponse;
use App\Response\EnumMessageResponseType;
use Illuminate\Http\Request;

use App\Exceptions\MyException;
use Throwable;

/**
 * This class is used to manipulate users.
 * A user can be company or person. 
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 * @author Fábio Sousa de Sant'Ana <fabio@4comtec.com.br>
 */
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

            // for security purpose.
            $input = sanitizeData($request->all()); 

            // call service store method and get new user info.
            $newUserInfo = $this->service->store($input);

            // encode API answering message with a specific ApiMessageResponse structure.
            $messageResponse = json_encode(new ApiMessageResponse(true, EnumMessageResponseType::Success, "Cadastrado com sucesso", $newUserInfo, null));

            return response($messageResponse, 200);

        } catch (Throwable $e) {

            //Call customized exception for handle error message.
            throw new MyException($e->getMessage(), $e->getCode(), $e, null);

        }
        
    }   

    /**
     * Get Total Balance of a user (person or company)             
     *      
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTotalBalance(Request $request) {

        try {
            
            // for security purpose.
            $input = sanitizeData($request->all());
           
            // call service store method, get total balance.
            $data["totalBalance"] = $this->service->getTotalBalance($input);

            // encode API answering message with a specific ApiMessageResponse structure.
            $messageResponse = json_encode(new ApiMessageResponse(true, EnumMessageResponseType::Success, null, $data, null));

            return response($messageResponse, 200);

        } catch (Throwable $e) {

            //Call customized exception for handle error message.
            throw new MyException($e->getMessage(), $e->getCode(), $e, null);

        }
        
    }       

}
