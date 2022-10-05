<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function sendEmail(
        $to = 'siteadmin@hotmail.fr',
        $subject = 'This is the Mail subject !',
        $from='',
        $content = '',
        $text = ''
    ): void {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($text)
            ->html($content);
        $this->mailer->send($email);
    }
}