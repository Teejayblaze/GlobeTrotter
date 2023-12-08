<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMails extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $dyn_content;
    private $mail_template;
    private $topic;
    private $group;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dyn_content, string $mail_template, string $subject, string $group = 'advertiser')
    {
        $this->dyn_content = $dyn_content;
        $this->mail_template = $mail_template;
        $this->topic = $subject;
        $this->group = $group;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->mail_template)
                ->subject($this->topic)
                ->with(['dyn_content' => $this->dyn_content, 'link' => url('/'. $this->group .'/verify/token/'. $this->dyn_content->token)]);
    }
}
