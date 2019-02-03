<?php

namespace Dniccum\CustomEmailSender\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomMessageMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var string $subject
     */
    public $subject;

    /**
     * @var string $message
     */
    public $message;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $message
     * @return void
     */
    public function __construct(string $subject, string $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = config('novaemailsender.template.view');

        if (!view()->exists($view)) {
            \View::addLocation(base_path('vendor/dniccum/custom-email-sender/resources/views'));
            $view = 'email';
        }

        $this->subject($this->subject)
            ->onQueue(config('novaemailsender.priority'))
            ->from(config('novaemailsender.from.address'), config('novaemailsender.from.name'))
            ->with([
                'content' => $this->message
            ]);

        if (config('novaemailsender.template.markdown')) {
            $this->markdown($view);
        } else {
            $this->html($view);
        }

        return $this;
    }
}
