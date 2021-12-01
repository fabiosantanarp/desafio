<?php

namespace App\DataAccess\User;
use App\Exceptions\MyException;
use App\DataAccess\DataAccess;

use App\Models\UserModel;
use App\Models\CompanyModel;
use App\Models\PersonModel;
use App\Models\OperationModel;

use Hash;
use Exception;
use App\Exceptions\MyBusinessException;

class UserDataAccess extends DataAccess {

    public function __construct(UserModel $model, CompanyModel $companymodel, PersonModel $personmodel) {

        $this->model = $model;
        $this->companymodel = $companymodel;
        $this->personmodel = $personmodel;

    }

    /**
     * get balance of a user.
     * @param Int   $idUser  Id of user  
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return Throw If any error
     * @return $totalBalance Total Balance of a specific user
     * 
    */ 
    public function getTotalBalanceUser($idUser) {

        $totalBalance = 0; // start total Balance.

        $operationModelObj = new OperationModel();

        //get all operation for user.
        $allUserOperation = $operationModelObj::where("idUser", $idUser)->get();

        if (count($allUserOperation) == 0) return 0;

        // loop all operation and credit or debit to the totalBalance variable.
        foreach ($allUserOperation as $key => $operation) {

            if ($operation->operationType == 'credit') {

                $totalBalance += $operation->operationValue;

            } else {

                $totalBalance -= $operation->operationValue;

            }
        }

        return $totalBalance;

    }    

    /**
     * Add User to database if not already created.
     * @param Array   $data  Information of a new user.     
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return Throw If it exists on database.
     * 
    */ 
    public function addUser($data) {

        \DB::beginTransaction();

            // check if user is already on the database.
            // Find user email in database and (cpf or cnpj) depends on typeUser.
            $foundUser = $this->model::where("email", $data["email"])->count() > 0 ||
                        ( $data["typeUser"] == 'person' 
                            ? $this->personmodel::where("cpf", $data["cpf"])->count() > 0 
                            : $this->companymodel::where("cnpj", $data["cnpj"])->count() > 0
                        );

            // If user not found, create one, else rollback transaction and generate exception.
            if (!$foundUser) {

                // Save generic data of user.
                $this->model->typeUser = $data["typeUser"];
                $this->model->email = $data["email"];
                $this->model->password = Hash::make($data["password"]);
                $this->model->createdAt = date("Y-m-d H:m");
                $this->model->updatedAt = null;
                $this->model->deletedAt = null;
                $this->model->save();

                // Save specific information of user.
                if ($data["typeUser"] == "company") {
                    $this->companymodel->idUser = $this->model->idUser;
                    $this->companymodel->cnpj = $data["cnpj"];
                    $this->companymodel->corporateName = $data["corporateName"];
                    $this->companymodel->canTransfer = false;
                    $this->companymodel->save();                
                } else {
                    $this->personmodel->idUser = $this->model->idUser;
                    $this->personmodel->cpf = $data["cpf"];
                    $this->personmodel->firstName = $data["firstName"];
                    $this->personmodel->lastName = $data["lastName"];
                    $this->personmodel->save();
                }

            } else {
                \DB::rollback();
                throw new MyBusinessException("Usuario já existe");
            }

        \DB::commit();

    }

    
}