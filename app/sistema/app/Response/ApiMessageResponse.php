<?php

namespace App\Response;

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