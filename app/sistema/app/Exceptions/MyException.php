<?php

namespace App\Exceptions;

use App\Response\ApiMessageResponse;
use App\Response\EnumMessageResponseType;
use Exception;
use Illuminate\Database\QueryExceptio;


/**
 * Custom Exception Class to handle returning message.
 * This class avoid exploding confidentials internal errors on the screen
 * @author Fábio Sant'Ana <fabio@4comtec.com.br>
 * @return Response
 * 
*/
class MyException extends Exception {

    public function __construct(string $message, $code = null, $previous = null) {  

        parent::__construct($message, $code, $previous);

        $this->exception = $previous;
        
    }

    /**
     * Render a message depending on exception type given.
     * Use a structured message response based on App\Response\ApiMessageResponse;
     * @param  \Illuminate\Http\Request  $request
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return Response
     * 
    */
    public function render($request) {

            switch (get_class($this->exception)) {
                case 'App\Exceptions\MyBusinessException'   : $message = $this->exception->getMessage(); break;                
                case 'App\Exceptions\MyValidationException' : $message = "Ocorreu um erro de validação. Consulte a documentação"; break;   
                case 'App\Exceptions\MyException'           : $message = "Ocorreu um erro geral. Por favor, tente novamente"; break;                  
                case 'ErrorException'                       : $message = "Ocorreu um erro geral. Por favor, tente novamente"; break;
                case 'Illuminate\Database\QueryException'   : $message = "Ocorreu um erro interno. Por favor, tente novamente"; break;
                default                                     : $message = "Ocorreu um erro desconhecido. Por favor, tente novamente"; break;
            }

            $messageResponse = json_encode(new ApiMessageResponse(false, EnumMessageResponseType::Error, $message, null, null));

            return response($messageResponse, 500);
        
    }
    
    
}
