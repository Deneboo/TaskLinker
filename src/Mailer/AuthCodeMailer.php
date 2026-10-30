<?php

namespace App\Mailer;

use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AuthCodeMailer implements AuthCodeMailerInterface
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function sendAuthCode(TwoFactorInterface $user): void
    {
        $authCode = $user->getEmailAuthCode();

        $email = (new Email())
            ->from('no-reply@mon-site.com')
            ->to($user->getEmailAuthRecipient())
            ->subject('Votre code de vérification 2FA')
            ->text("Votre code de vérification est : {$authCode}")
            ->html("<p>Votre code de vérification est : <strong>{$authCode}</strong></p>");

        $this->mailer->send($email);
    }
}
