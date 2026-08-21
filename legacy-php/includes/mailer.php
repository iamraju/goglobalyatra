<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function build_site_mail_template(array $config, string $subject, string $contentHtml): string
{
    $site = $config['site'] ?? [];
    $siteName = e((string) ($site['name'] ?? 'Website'));
    $siteTagline = e((string) ($site['tagline'] ?? ''));
    $siteEmail = e((string) ($site['topbar_email'] ?? ''));
    $sitePhone = e((string) ($site['topbar_phone'] ?? ''));

    $logoPath = (string) ($site['logo'] ?? '');
    $logoUrl = $logoPath !== '' ? absolute_url($logoPath) : '';
    $safeLogoUrl = e($logoUrl);
    $safeSubject = e($subject);

    $logoBlock = '';
    if ($logoUrl !== '') {
        $logoBlock = '<img src="' . $safeLogoUrl . '" alt="' . $siteName . '" style="display:block;max-height:54px;width:auto;border:0;outline:none;text-decoration:none;">';
    } else {
        $logoBlock = '<div style="font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;">' . $siteName . '</div>';
    }

    return '<!doctype html>'
        . '<html lang="en">'
        . '<head>'
        . '  <meta charset="UTF-8">'
        . '  <meta name="viewport" content="width=device-width, initial-scale=1.0">'
        . '  <title>' . $safeSubject . '</title>'
        . '</head>'
        . '<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">'
        . '  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f1f5f9;padding:24px 12px;">'
        . '    <tr>'
        . '      <td align="center">'
        . '        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:640px;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">'
        . '          <tr>'
        . '            <td style="background:linear-gradient(90deg,#0f172a,#1d4ed8);padding:18px 24px;">'
        . '              <table role="presentation" cellpadding="0" cellspacing="0" width="100%">'
        . '                <tr>'
        . '                  <td align="left" valign="middle">' . $logoBlock . '</td>'
        . '                </tr>'
        . '              </table>'
        . '            </td>'
        . '          </tr>'
        . '          <tr>'
        . '            <td style="padding:24px 24px 8px 24px;">'
        . '              <h1 style="margin:0;font-size:20px;line-height:1.35;color:#0f172a;">' . $safeSubject . '</h1>'
        . '            </td>'
        . '          </tr>'
        . '          <tr>'
        . '            <td style="padding:8px 24px 24px 24px;font-size:15px;line-height:1.7;color:#334155;">'
        .                    $contentHtml
        . '            </td>'
        . '          </tr>'
        . '          <tr>'
        . '            <td style="padding:14px 24px 20px 24px;border-top:1px solid #e2e8f0;background:#f8fafc;">'
        . '              <p style="margin:0;font-size:12px;line-height:1.7;color:#64748b;">'
        . '                ' . $siteName . ($siteTagline !== '' ? ' | ' . $siteTagline : '') . '<br>'
        . '                ' . $siteEmail . ($sitePhone !== '' ? ' | ' . $sitePhone : '')
        . '              </p>'
        . '            </td>'
        . '          </tr>'
        . '        </table>'
        . '      </td>'
        . '    </tr>'
        . '  </table>'
        . '</body>'
        . '</html>';
}

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
        $templatedBody = build_site_mail_template($config, $subject, $htmlBody);
        $mail->Subject = $subject;
        $mail->Body = $templatedBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));

        $mail->send();
        return [true, null];
    } catch (Exception $exception) {
        return [false, $exception->getMessage()];
    }
}
