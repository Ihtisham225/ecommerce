<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryReply extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;
    public $replyMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactInquiry $inquiry, $replyMessage)
    {
        $this->inquiry = $inquiry;
        $this->replyMessage = $replyMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Re: ' . $this->inquiry->subject)
                    ->view('emails.inquiry-reply');
    }
}