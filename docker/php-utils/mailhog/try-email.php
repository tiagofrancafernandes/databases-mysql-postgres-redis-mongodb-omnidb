<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../packages/custom_autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$authData = [
    'Host' => $_SERVER['MAILHOG_HOST'] ?? 'mailhog.local',
    'SMTPAuth' => $_SERVER['MAILHOG_SMTP_AUTH'] ?? false,
    'Username' => $_SERVER['MAILHOG_USERNAME'] ?? null,
    'Password' => $_SERVER['MAILHOG_PASSWORD'] ?? null,
    'SMTPSecure' => $_SERVER['MAILHOG_SMTP_SECURE'] ?? null,
    'Port' => $_SERVER['MAILHOG_SMTP_PORT'] ?? 1025,
];

// die(var_export($authData));

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;              // Enable verbose debug output
    $mail->isSMTP();                                    // Send using SMTP
    $mail->Host       = $authData['Host'];              // Set the SMTP server to send through
    $mail->SMTPAuth   = (bool) $authData['SMTPAuth'];   // Enable SMTP authentication
    $mail->Username   = $authData['Username'];          // SMTP username
    $mail->Password   = $authData['Password'];          // SMTP password
    $mail->SMTPSecure = $authData['SMTPSecure'];        // Enable implicit TLS encryption
    $mail->Port       = $authData['Port'];              // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`


    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    // ATTACHMENTS
    // Add attachments
    $mail->addAttachment(__DIR__ . '/file.txt');

    // Optional name
    $mail->addAttachment(__DIR__ . '/file.txt', 'file-with-new-name.txt');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
