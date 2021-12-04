<?php

namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Support\Str;

use App\Models\PersonModel;
use App\Models\CompanyModel;  

class TransactionTest extends TestCase {

    //Return a random field based on type param.
    private function create_random_fields($type) {

        if ($type == "cpf"){
            return rand(100,999). "." .rand(100,999). "." .rand(100,999). "-00";
        } else if  ($type == "cnpj") {
            return rand(10,99). "." .rand(100,999). "." .rand(100,999). "/0001-00";
        }       
    }
    
    private function create_user_person($giveInitialCredit = true) {

        $randomString = Str::random(5);
        $createUserResponse = $this->post('/api/user/add',
        [
            "typeUser"  => "person",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "firstName" => $randomString,
            "lastName"  => $randomString,
            "cpf"       => $this->create_random_fields("cpf")
        ]);

        if ($giveInitialCredit == true) {

            // transfer credit to person for testing.
            $trasferToNewUser = $this->post('/api/transaction/add',
            [
                "idUserPayer"  => 1,
                "idUserPayee"  => $createUserResponse["data"]["userId"],
                "operationValue"  => '100'
            ]); 
            
        }
       
        return $createUserResponse;        
    }

    private function create_user_company($giveInitialCredit = true) {

        $randomString = Str::random(5);
        $createUserResponse = $this->post('/api/user/add',
        [
            "typeUser"  => "company",
            "email"     => $randomString."@testuser.com",
            "password"  => $randomString ,
            "corporateName" => $randomString,            
            "cnpj"       => $this->create_random_fields("cnpj")
        ]);

        if ($giveInitialCredit == true) {

            // transfer credit to company for testing.
            $trasferToNewUser = $this->post('/api/transaction/add',
            [
                "idUserPayer"  => 1,
                "idUserPayee"  => $createUserResponse["data"]["userId"],
                "operationValue"  => '100'
            ]);

        }
        
        return $createUserResponse;        
    }
    
    public function test_company_matrix_can_transfer_user() {

        $idNewPerson  = $this->create_user_person()["data"]["userId"];

        $response = $this->post('/api/transaction/add',
        [
            "idUserPayer"  => 1,
            "idUserPayee"  => $idNewPerson,
            "operationValue"  => '10.00'
        ]);    

        $response->assertStatus(200)
        ->assertJson([
            "success" => true,
            "type" => "Success",
            "text" => "Transferência realizada com sucesso"
        ]);

    } 

    public function test_company_not_matrix_can_not_transfer_user() {

        //Create new company
        $idNewCompany  = $this->create_user_company()["data"]["userId"];
        //Get a person from Model
        $idPerson  = \App\Models\PersonModel::first();

        $response = $this->post('/api/transaction/add',
        [
            "idUserPayer"  => $idNewCompany,
            "idUserPayee"  => $idPerson["idUser"],
            "operationValue"  => '10.00'
        ]); 
        
        $response->assertStatus(500)
        ->assertJson([
            "text" => "Lojista não pode transferir"
        ]);

    }    

    public function test_person_can_transfer_to_company() {

        //Create new person
        $idPerson  = $this->create_user_person(true)["data"]["userId"];
        //Get a company from Model
        $idNewCompany  = \App\Models\CompanyModel::first(); 
       
        $response = $this->post('/api/transaction/add',
        [
            "idUserPayer"  => $idPerson,
            "idUserPayee"  => $idNewCompany["idUser"],
            "operationValue"  => '1.00'
        ]);  
        
        $response->assertStatus(200)
        ->assertJson([
            "success" => true,
            "type" => "Success",
            "text" => "Transferência realizada com sucesso"
        ]);

    }  

    public function test_user_can_not_transfer_to_himself() {

        $idNewPerson  = $this->create_user_person()["data"]["userId"];

        $response = $this->post('/api/transaction/add',
        [
            "idUserPayer"  => $idNewPerson,
            "idUserPayee"  => $idNewPerson,
            "operationValue"  => '10.00'
        ]);                    

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Transferências não podem ser realizadas para si mesmo"
        ]);        

    }   
    
    public function test_user_no_credit_can_not_transfer() {

        //create a user with no credit.
        $idNewPerson  = $this->create_user_person(false)["data"]["userId"];

        //Get a person from Model
        $idPerson  = \App\Models\PersonModel::first(); 
      
        $response = $this->post('/api/transaction/add',
        [
            "idUserPayer"  => $idNewPerson,
            "idUserPayee"  => $idPerson["idUser"],
            "operationValue"  => '10.00'
        ]);                    

        $response->assertStatus(500)
        ->assertJson([
            "success" => false,
            "type" => "Error",
            "text" => "Usuário não possui saldo suficiente"
        ]);        

    }   
    
   
    
    
}
