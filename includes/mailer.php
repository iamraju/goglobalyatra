<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function send_site_mail(array $config, string $subject, string $htmlBody, ?string $replyTo = null): array
{
    $autoloadPath = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoloadPath)) {
        return [false, 'Mail library not installed. Run composer install.'];
    }

    require_once $autoloadPath;

    $mailConfig = $config['mail'];
    $mail = new PHPMailer(true);

    try {
        $mailerType = strtolower((string) ($mailConfig['mailer'] ?? 'mail'));
        if ($mailerType === 'smtp') {
            $mail->isSMTP();
            $mail->Host = (string) $mailConfig['host'];
            $mail->Port = (int) $mailConfig['port'];
            $mail->SMTPAuth = true;
            $mail->Username = (string) $mailConfig['username'];
            $mail->Password = (string) $mailConfig['password'];

            $encryption = strtolower((string) ($mailConfig['encryption'] ?? 'tls'));
            if ($encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
        } else {
            $mail->isMail();
        }

        $mail->setFrom((string) $mailConfig['from_email'], (string) $mailConfig['from_name']);
        $mail->addAddress((string) $mailConfig['to_email']);

        if ($replyTo !== null && $replyTo !== '') {
            $mail->addReplyTo($replyTo);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));

        $mail->send();
        return [true, null];
    } catch (Exception $exception) {
        return [false, $exception->getMessage()];
    }
}
