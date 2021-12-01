<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\UserModel;
use App\Services\Transaction\EnumNotificationTemplate;

/**
 * Specific class for email notification.
 * @param idUser User Id.
 * @param template Template for Email.
 * @author Fábio Sant'Ana <fabio@4comtec.com.br>
 * @return void
*/
class SendMailUser extends Mailable
{
    use Queueable, SerializesModels;

    public $notificationData;
    public $template;
    public $informationPayer;
    public $informationPayee;

    /**
     * Create a new message instance based on template.
     * @param idUser User Id.
     * @param template Template for Email.
     * @author Fábio Sant'Ana <fabio@4comtec.com.br>
     * @return void
    */
    public function __construct(array $notificationData, string $template)
    {
        $this->notificationData = $notificationData;
        $this->template = $template;

        // receive info about user;
        $this->informationPayer = getUserInfo([ $notificationData["idUserPayer"] ])[0];
        $this->informationPayee = getUserInfo([ $notificationData["idUserPayee"] ])[0]; 

    }

    /**
     * Build the message depends on template type.
     *
     * @return $this
     * @author Fábio Sant'Ana <fabio@4comtec.com.br> 
     */
    public function build()
    {

        switch ($this->template) {
            case EnumNotificationTemplate::ReceivedPayment:
                $emailTo = UserModel::where("idUser", $this->informationPayee["idUser"])->pluck("email")[0];
                return $this->view('mail.receivedPaymentMail')->to($emailTo)->subject('Você recebeu um novo pagamento');
                break;
        }
      
    }
}
