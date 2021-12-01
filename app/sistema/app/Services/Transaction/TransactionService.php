<?php

namespace App\Services\Transaction;
use App\Services\Service;
use App\Validators\Transaction\TransactionValidator;
use App\DataAccess\Transaction\TransactionDataAccess;
use App\Services\Transaction\EnumNotificationMethod;
use App\Services\Transaction\EnumNotificationTemplate;

use App\Exceptions\MyBusinessException;
use Illuminate\Support\Facades\Http;

use Mail;
use App\Mail\SendMailUser;


class TransactionService extends Service {

    public function __construct(TransactionValidator $validator, TransactionDataAccess $dataaccess) {

        $this->validator = $validator;        
        $this->dataaccess = $dataaccess;

    }

    /**
    * Stores a new transaction specifically for credit transfers between users.
    * The credit transaction consists of a debit (payer) and credit (payee) transaction.
    * A notification will be forwarded.
    * @param Array   $data  User information     
    * @author Fábio Sant'Ana <fabio@4comtec.com.br>
    * 
    */ 
    public function store(array $data) {  

        // Required fields.
        $requiredFields  = ['idUserPayer', 'idUserPayee', 'operationValue'];

        //Check required fields in data.
        $notFoundFields = $this->hasInArray($requiredFields, $data);

        if (count($notFoundFields) > 0) {              

            throw new MyBusinessException("Dados nao encontrados (" . implode(", ", $notFoundFields) . "). Verifique a documentacao.");

        }

        // check it is a myself transfering.
        if ($data["idUserPayer"] == $data["idUserPayee"]) {

            throw new MyBusinessException("Transferências não podem ser realizadas para si mesmo");

        }


        // check if company can transfers.

        $infoCompany = $this->dataaccess->getCompany($data["idUserPayer"]); //get information about company

        if (isset($infoCompany) && count($infoCompany->toArray()) > 0) {

            if ($infoCompany["canTransfer"] == 0) { //check if this specific company can transfer. Remember: Default is false.

                throw new MyBusinessException("Lojista não pode transferir");

            }
            
        }        

        // insert a transaction and operations.
        $this->dataaccess->store($data["idUserPayer"], $data["idUserPayee"], $data["operationValue"]);

        //notify user
        $this->__notifyUser($data, 'email', 'receivedPayment');

    }
    
    /**
    * Generic method for notification through templates.
    * @param Array   $notificationData  Data Notification.
    * @param String   $notificationMethod  Method used for notify
    * @param String   $template  Specific template for sending. 
    * @author Fábio Sant'Ana <fabio@4comtec.com.br>
    * 
    */ 
    private function __notifyUser(array $notificationData, string $notificationMethod = EnumNotificationMethod::Email, string $template = EnumNotificationTemplate::ReceivedPayment) {

        // check simulation external server.
        $response = Http::get(env("NOTIFICATION_SENDER_MOCK"));         

        if ($response->status() <> 200 && !$response->successful()) {

           return false;
            
        } else {

            // payment notification
            if ($template == EnumNotificationTemplate::ReceivedPayment) {

                switch ($notificationMethod) {
                    case EnumNotificationMethod::Email:
                        $this->__notifyByEmail($notificationData, $template);
                        break;

                    // ... others methods
                }
                
            }

        }

    }

    private function __notifyByEmail(array $notificationData, string $template) {

        Mail::queue(new SendMailUser($notificationData, $template));

    }

}    