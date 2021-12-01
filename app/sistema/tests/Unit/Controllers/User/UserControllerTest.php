<?php

namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserTest extends TestCase {     
    
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

    public function test_create_user_person()
    {
        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => $this->create_random_fields("cpf")
        ]);                    

        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "type" => "Success",
                "text" => "Cadastrado com sucesso"
            ]);;

    }  

    public function test_create_company()
    {

        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
            "typeUser"      => "company",
            "email"         => $randomString."@testcompany.com",
            "password"      => $randomString,
            "cnpj"          => $this->create_random_fields("cnpj"),
            "corporateName" => "Company ".$randomString,
        ]);

        $response->assertStatus(200)
        ->assertJson([
            "success" => true,
            "type" => "Success",
            "text" => "Cadastrado com sucesso"
        ]);;

    }    

    public function test_create_person_without_cpf()
    {
        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
            "typeUser"=> "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString            
        ]);

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Dados nao encontrados (cpf). Verifique a documentacao."
        ]);

    }

    public function test_create_company_without_cnpj()
    {
        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
            "typeUser"      => "company",
            "email"         => $randomString."@testcompany.com",
            "password"      => $randomString,
            "corporateName" => "Company ".$randomString,           
        ]);

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Dados nao encontrados (cnpj). Verifique a documentacao."
        ]);

    }   
    
    public function test_create_company_already_created()
    {
        $randomString = Str::random(5);

        $userTemplate =  [
            "typeUser"      => "company",
            "email"         => $this->create_random_fields("email"),
            "password"      => $randomString,
            "corporateName" => "Company ".$randomString,   
            "cnpj"          =>  $this->create_random_fields("cnpj")                  
        ];        

        $responseCreateFirstUser = $this->post('/api/user/add', $userTemplate);

        $responseCreateSecondUser = $this->post('/api/user/add', $userTemplate);

        $responseCreateSecondUser->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Usuario já existe"
        ]);
    }  
    
    public function test_create_user_person_with_duplicated_cpf()
    {
        $randomString = Str::random(5);

        $cpfExample =  $this->create_random_fields("cpf");

        $userTemplate = [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => $cpfExample
        ];

        $responseCreateFirstUser = $this->post('/api/user/add', $userTemplate);   
        
        $responseCreateSecondUser = $this->post('/api/user/add',$userTemplate);

        $responseCreateSecondUser->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Usuario já existe"
        ]);
    }  

    public function test_create_user_person_with_duplicated_email()
    {
        $randomString = Str::random(5);

        $userTemplate = [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => $this->create_random_fields("cpf")
        ];

        $responseCreateFirstUser = $this->post('/api/user/add', $userTemplate);   
        
        $responseCreateSecondUser = $this->post('/api/user/add',$userTemplate);

        $responseCreateSecondUser->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Usuario já existe"
        ]);
    }  
    
    public function test_create_user_with_strange_type_user()
    {
        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
            "typeUser"      => "NOTVALID", //here is the invalid typeuser.
            "email"         => $randomString."@testcompany.com",
            "password"      => $randomString,
            "corporateName" => "Company ".$randomString,
            "cnpj"          => $this->create_random_fields("cnpj")
        ]);       

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Tipo de Usuário (typeUser) inválido"
        ]);
    }     

    public function test_create_company_without_param()
    {
        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
      
        ]);        

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Ocorreu um erro geral. Por favor, tente novamente"
        ]);
    }
    
    public function test_create_company_with_validation_error()
    {
        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
            "typeUser"      => "company",
            "email"         => "HERE_NOT_A_EMAIL", //here is a invalid email format.
            "password"      => $randomString,
            "corporateName" => "Company ".$randomString,
            "cnpj"          => $this->create_random_fields("cnpj")
        ]);

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Ocorreu um erro de validação. Consulte a documentação"
        ]);
    }  
}
