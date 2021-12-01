<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Transaction\TransactionService;

use App\Response\ApiMessageResponse;
use App\Response\EnumMessageResponseType;

use App\Exceptions\MyException;
use Throwable;

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

            $input = sanitizeData($request->all()); 
            
            $this->service->store($input);

            $messageResponse = json_encode(new ApiMessageResponse(true, EnumMessageResponseType::Success, "Transferência realizada com sucesso", $input, null));

            return response($messageResponse, 200);

        } catch (Throwable $e) {               
            
            throw new MyException($e->getMessage(), $e->getCode(), $e, null);

        }
        
    } 

}
