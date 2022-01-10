<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Card;


class TripInvoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $UserRequest;
    public $paymentCard;

    public function __construct($UserRequest)
    {
        $this->UserRequest=$UserRequest;
        $this->paymentCard=Card::where('user_id',$UserRequest->user_id)->where('is_default',1)->first();

       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.trip_invoice');
    }
}
