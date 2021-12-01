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

        $response->assertStatus(200);

    }  

    public function test_create_company()
    {

        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
            "typeUser"      => "company",
            "email"         => $randomString."@testcompany.com",
            "password"      => $randomString,
            "cnpj"          => "00.000.123/0001-00",
            "corporateName" => "Company ".$randomString,
        ]);

        $response->assertStatus(200);

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
            "lastName"  => $randomString,            
        ]);

        $response->assertStatus(500);
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

        $response->assertStatus(500);
    }   
    
    public function test_create_company_duplicated()
    {
        $randomString = Str::random(5);
        $response1 = $this->post('/api/user/add',
        [
            "typeUser"      => "company",
            "email"         => $randomString."@testcompany.com",
            "password"      => $randomString,
            "corporateName" => "Company ".$randomString,           
        ]);

        $response2 = $this->post('/api/user/add',
        [
            "typeUser"      => "company",
            "email"         => $randomString."@testcompany.com",
            "password"      => $randomString,
            "corporateName" => "Company ".$randomString,           
        ]);        

        $response2->assertStatus(500);
    }  
    
    public function test_create_user_person_duplicated()
    {
        $randomString = Str::random(5);
        $response1 = $this->post('/api/user/add',
        [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => "228.776.518-99"
        ]);   
        
        $response2 = $this->post('/api/user/add',
        [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => "228.776.518-99"
        ]);         

        $response2->assertStatus(500);

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
        ]);       

        $response->assertStatus(500);
    }     

    public function test_create_company_without_param()
    {
        $randomString = Str::random(5);
        $response = $this->post('/api/user/add',
        [
      
        ]);

        $response->assertJson([
            "success" => false
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
        ]);

        $response->assertJson([
            "success" => false
        ]);
    }  
    
    // public function test_get_total_balance_of_user()
    // {
    //     $response = $this->get('/api/user/getTotalBalance',
    //     [
    //         "idUser" => 1,            
    //     ]);

    //     $response->assertStatus(200);

    // }      


    
    
}
