<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CancelSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    protected $plan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $plan)
    {

        $this->user = $user;
        $this->plan = $plan;

    }
    public function build()
    {
        return $this->markdown('email.cancel_subscription')->with(['user'=>$this->user, 'plan'=>$this->plan]);

        // return $this->view('view.name');
    }
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject:'Cancel subscription',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view:'view.name',
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
