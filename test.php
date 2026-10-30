<?php

require 'vendor/autoload.php';

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

$transport = Transport::fromDsn('smtp://127.0.0.1:1025');
$mailer = new Mailer($transport);

$email = (new Email())
    ->from('test@tasklinker.local')
    ->to('destinataire@example.com')
    ->subject('Test Mailpit Direct !')
    ->text('Ça fonctionne !');

$mailer->send($email);
echo "Email envoyé avec succès !\n";
