<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Transaction\TransactionService;

use App\Response\ApiMessageResponse;
use App\Response\EnumMessageResponseType;

use App\Exceptions\MyException;
use Throwable;

/**
 * This class is used to perform transfers between users.
 * A transfer transaction can be carried out between companies and people. 
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 * @author Fábio Sousa de Sant'Ana <fabio@4comtec.com.br>
 */
class TransactionController extends Controller
{
    public function __construct(TransactionService $service) {

        $this->service = $service;

    }

    /**
     * Create a new transaction between users.
     *      
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @author Fábio Sousa de Sant'Ana <fabio@4comtec.com.br>
     */
    public function store(Request $request) {

        try {

            // for security purpose.
            $input = sanitizeData($request->all()); 
            
            // call service store method.
            $this->service->store($input);

            // encode API answering message with a specific ApiMessageResponse structure.
            $messageResponse = json_encode(new ApiMessageResponse(true, EnumMessageResponseType::Success, "Transferência realizada com sucesso", $input, null));

            return response($messageResponse, 200);

        } catch (Throwable $e) {         

            //Call customized exception for handle error message.
            throw new MyException($e->getMessage(), $e->getCode(), $e, null);

        }
        
    } 

}
