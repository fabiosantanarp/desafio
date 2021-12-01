<?php

namespace App\DataAccess\Transaction;
use App\Exceptions\MyException;
use App\DataAccess\DataAccess;

use App\Models\TransactionModel;
use App\Models\OperationModel;

use Exception;
use App\Exceptions\MyBusinessException;
use Illuminate\Support\Facades\Http;

class TransactionDataAccess extends DataAccess {

    public function __construct(TransactionModel $model, OperationModel $operationmodel) {

        $this->model = $model;
        $this->operationmodel = $operationmodel;

    }

    /**
     * Check balance of a user.
     * @param Array   $data  Information of transfer   
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return Throw If any error
     * 
    */ 
    public function getTotalBalanceUser($idUser) {

        $totalBalance = 0; // start total Balance.

        //get all operation for user.
        $allUserOperation = $this->operationmodel::where("idUser", $idUser)->get();

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
     * Return specific company data.
     * @param Int $idUser  User's Id.  
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return \App\Models\CompanyModel User Model.
     * 
    */ 
    public function getCompany(int $idUser)  {

        return \App\Models\CompanyModel::where("idUser", $idUser)->get()[0] ?? null;

    }  

    /**
     * Return specific person.
     * @param Int $idUser  User's Id.  
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return \App\Models\PersonModel User Model.
     * 
    */ 
    public function getPerson(int $idUser)  {

        return \App\Models\PersonModel::where("idUser", $idUser)->get()[0] ?? null;

    }        

    /**
     * Return specific user.
     * @param Int $idUser  User's Id.  
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return \App\Models\UserModel User Model.
     * 
    */ 
    public function getUser(int $idUser)  {

        return \App\Models\UserModel::find($idUser);

    }

    /**
     * A new transfer transaction between users is carried out only if the payer has an available balance.
     * An external authorizer will be consulted to validate the transaction.
     * A transfer consists of two operations: debit to the payer and credit to the beneficiary
     * @param Int $idUserPayer  Paying user id .  
     * @param Int $idUserPayee  Paid user id. 
     * @param Int $operationValue  Receiving user id.      
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return MyException Throw Exception if any verification is breaked.
     * 
    */ 
    public function store(int $idUserPayer, int $idUserPayee, float $operationValue) {

        \DB::beginTransaction();

            // how much balance the payer has.
            $totalBalance = $this->getTotalBalanceUser($idUserPayer);

            if ($totalBalance < $operationValue) {
                \DB::rollback();
                throw new MyBusinessException("Usuário não possui saldo suficiente");
            }

            //create a transaction.
            $idNewTransaction = $this->__newTransaction($idUserPayer, $idUserPayee);

            //check authorization            
            if ($this->__checkAuthorization() ==  false) {
                \DB::rollback();
                throw new MyBusinessException("Operação não permitida");
            };            

            //create debit operation.
            $this->__newOperation($idUserPayer, $idNewTransaction, 'debit', $operationValue);

            //create credit operation.        
            $this->__newOperation($idUserPayee, $idNewTransaction, 'credit', $operationValue); 
        
        \DB::commit();

    }

    private function __newTransaction(int $idUserPayer, $idUserPayee) {
        $newTransactionObj = new $this->model;
        $newTransactionObj->idUserPayer = $idUserPayer;
        $newTransactionObj->idUserPayee = $idUserPayee;
        $newTransactionObj->createdAt = date("Y-m-d H:i");
        $newTransactionObj->save();
        return $newTransactionObj->idTransaction;
    }

    private function __newOperation(int $idUser, int $idTransaction, string $operationType, $operationValue) {
        $newOperationObj = new $this->operationmodel;
        $newOperationObj->idUser = $idUser;
        $newOperationObj->idTransaction = $idTransaction;
        $newOperationObj->operationType = $operationType;
        $newOperationObj->operationValue = $operationValue;
        $newOperationObj->save();
        return $newOperationObj->idOperation;
    }    

    private function __checkAuthorization() {

         // check simulation external authorization.
         $response = Http::get(env("EXTERNAL_AUTHORIZATION_MOCK"));         

         if ($response->status() <> 200 && !$response->successful()) {
 
            return false;
             
         } else {

             return true;

         }

    }
    
}