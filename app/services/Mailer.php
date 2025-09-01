<?php
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public static function make(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = env('MAIL_HOST', 'localhost');
        $mail->Port       = (int)env('MAIL_PORT', 25);

        $enc = strtolower((string)env('MAIL_ENCRYPTION', ''));
        if ($enc === 'tls')  { $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; }
        if ($enc === 'ssl')  { $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; }

        $user = env('MAIL_USERNAME', '');
        $pass = env('MAIL_PASSWORD', '');
        $mail->SMTPAuth   = $user !== '' || $pass !== '';
        $mail->Username   = $user;
        $mail->Password   = $pass;

        $mail->CharSet    = 'UTF-8';
        $mail->setFrom(env('MAIL_FROM', 'no-reply@psihoservice.local'), env('MAIL_FROM_NAME', 'PsihoService'));
        $mail->SMTPDebug  = (int)env('MAIL_DEBUG', 0);
        return $mail;
    }

    public static function send(string $to, string $subject, string $html, string $textAlt = ''): bool
    {
        $mail = self::make();
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body    = $html;
        $mail->AltBody = $textAlt !== '' ? $textAlt : strip_tags($html);
        return $mail->send();
    }
}
