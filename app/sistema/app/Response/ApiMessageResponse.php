<?php

namespace App\Response;

/**
* Response structure for Api.
* A notification will be structured.
* @author Fábio Sant'Ana <fabio@4comtec.com.br>
* 
*/ 
class ApiMessageResponse {
    
    public $success;
    public $type;
    public $text;
    public $data;
    public $other;
    
    public function __construct ($success, $type, $text, $data, $other = null) {

        $this->success = $success;
        $this->type = $type;
        $this->text = $text;
        $this->data = $data;
        $this->other = $other;
    }
    
}