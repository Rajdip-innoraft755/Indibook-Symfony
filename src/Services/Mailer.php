<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Require this file to use the variable which are defined as
// constant inside this file.
require '../src/Services/Secrets.php';

/**
 * Mailer class is to send mail to a perticular email through PHPMailer.
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class Mailer
{
  /**
   * This sendmail methods accepts the email address, mail subject
   * and mail body as input parameter and send the mail to that user using
   * PHPMailer and it uses the constant declared in Secret.php as the
   * credentials needed by PHPMailer.
   *
   *   @param string $address
   *     Takes email address to which the mail need to send.
   *
   *   @param string $subject
   *     Takes the email subject.
   *
   *   @param string $body
   *     Takes the body of the mail which is sent in the email.
   *
   *   @return void
   *     This method is just to send mail returns nothing.
   *
   */
  public function sendmail(string $address, string $subject, string $body)
  {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = MAILHOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAILUSERNAME;
    $mail->Password = MAILPASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom(MAILUSERNAME, 'info@indibook.com');
    $mail->addAddress($address);
    $mail->addReplyTo(MAILUSERNAME, 'info@indibook.com');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->send();
  }
}
?>

