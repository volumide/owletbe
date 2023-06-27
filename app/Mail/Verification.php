<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use MailchimpTransactional\ApiClient;

class Verification extends Mailable
{
    use Queueable, SerializesModels;
    protected $mailchimp;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mailchimp = new ApiClient();
        $this->mailchimp->setApiKey(env('MAIL_PASSWORD'));
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope():Envelope
    {
        return new Envelope(
            from:new Address("owletpay@noreply.com"),
            subject: 'Verification',
        );
    }

    public function sendMail($to, $subject, $content, $from="getonboard@owletpay.com"){
        // $this->mailchimp->messages
        $response = $this->mailchimp->messages->send([
            'message' => [
                'from_email' => $from,
                'subject' => $subject,
                'html' => $content,
                'to' => [
                    [
                        'email' => $to,
                        'type' => 'to'
                    ]
                ]
            ]
        ]);

        return $response;
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}