<?php

namespace Tests\Unit;
use Tests\TestCase;

use Illuminate\Support\Str;

class ApiTest extends TestCase {

    //Return a random field based on type param.
    private function create_random_fields($type) {

        if ($type == "cpf"){
            return rand(100,999). "." .rand(100,999). "." .rand(100,999). "-00";
        } else if  ($type == "cnpj") {
            return rand(10,99). "." .rand(100,999). "." .rand(100,999). "/0001-00";
        }else if ($type == "email") {
            return Str::random(10)."@".Str::random(10).".com.br";
        }
    }
    
    public function test_user_can_login_with_correct_credentials()
    {
        $response = $this->post('/api/auth/login',
        [
            "email"     => "autentica@api.com",
            "password"  => "123456",
        ]); 
       
        $response->assertStatus(200)
        ->assertJson([
            "token_type" => 'bearer',
            "expires_in" => "3600",
        ]);        
    }  
    
    public function test_user_can_login_with_incorrect_credentials()
    {
        $response = $this->post('/api/auth/login',
        [
            "email"     => "INCORRECT_CREDENTIAL",
            "password"  => "INCORRETCT_PASSWORD",
        ]); 
       
        $response->assertStatus(401)
        ->assertJson([
            "error" => 'Unauthorized',
        ]);        
    } 
    
    
    public function test_call_any_method_without_credentials()
    {
        $response = $this->post('/api/user/add',
        [
            "typeUser"  => "person",
            "email"     => $this->create_random_fields("email"),
            "password"  => "123456" ,
            "firstName" => "Any",
            "lastName"  => "User",
            "cpf"       => $this->create_random_fields("cpf")
        ]);      

        $response->assertStatus(200)
            ->assertJson([
                "status" => "Authorization Token not found"
            ]);

    } 
   
    
}
