<?php

namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserTest extends TestCase {       

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
            "cpf"       => rand(000,999).".776.518-99"
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
            "cnpj"          => "13.123.112/".rand(0000,9999)."-88",
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
            "email"         => $randomString."@testcompany.com",
            "password"      => $randomString,
            "corporateName" => "Company ".$randomString,   
            "cnpj"          => "13.123.112/".rand(0000,9999)."-88"        
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
    
    public function test_create_user_person_duplicated()
    {
        $randomString = Str::random(5);
        $responseCreateFirstUser = $this->post('/api/user/add',
        [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => "228.776.518-99"
        ]);   
        
        $responseCreateSecondUser = $this->post('/api/user/add',
        [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => "228.776.518-99"
        ]);         

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
            "cnpj"          => "13.123.112/".rand(0000,9999)."-88"           
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
            "cnpj"          => "13.123.112/".rand(0000,9999)."-88"           
        ]);

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Ocorreu um erro de validação. Consulte a documentação"
        ]);
    }  
}
