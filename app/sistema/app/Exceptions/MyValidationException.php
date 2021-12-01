<?php

namespace App\Exceptions;
use Exception;

/**
 * Custom Exception Class to handle returning message about validation.
 * @author Fábio Sant'Ana <fabio@4comtec.com.br>
 * 
*/
class MyValidationException extends Exception
{
    public function __construct(string $message, $code = null, Exception $previous = null) {     
   
        parent::__construct($message, $code, $previous);
        
    }
}
