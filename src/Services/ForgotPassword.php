<?php
namespace App\Services;

use Exception;
use App\Services\Mailer;
use App\Repository\UserRepository;

/**
 * ForgotPassword class is for validate the userId to check whether the
 * userId is coorect or not, generate and  send the otp to the registered
 * email id of the user, verify the otp .
 *
 * @author rajdip <rajdip.roy@innoraft.com>
 */
class ForgotPassword
{

  /**
   * This is a object of Mailer class, this helps to send mail.
   *
   *   @var object
   */
  public $mailer;

  /**
   * This constructor initializes object of ForgotPassword Class also provides
   * access to EntityManagerInterface object .
   *
   * @return void
   * Constructor returns nothing .
   */
  public function __construct()
  {
    $this->mailer = new Mailer();
  }

  /**
   * This method is used to check whether the user id is valid or not, if
   * exists then store the email id as cookie variable.
   *
   *   @param string $userId
   *     Accepts the user id which is needed to be checked.
   *
   *   @param UserRepository $em
   *     Accepts UserRepository object to retrieve the data of user.
   *
   *   @return string
   *     Returns the message whether the user id valid or invalid.
   */
  public function checkUser(UserRepository $userRepo, string $userId): string
  {
    if ($userRepo->findOneBy(array("userId" => $userId))) {
      $user = $userRepo->findOneBy(array("userId" => $userId));
      setcookie("emailId", base64_encode($user->getEmailId()),15/1440,"/");
      return "* Valid user ID.";
    }
    return "* Invalid user ID.";
  }

  /**
   * This method is to generate the otp and send the otp to the registered
   * email id of the user and store the otp in a cookie variable to match it
   * in future.
   *
   *   @return string
   *     Returns the message whether the otp sent successfully or not.
   */
  public function sendOtp(): string
  {
    $otp = rand(10000, 99999);
    $address = base64_decode($_COOKIE["emailId"]);
    $body = "Dear User ,<br><br> Here is your OTP. Please , don't share it with Others. <br><br> OTP : " . $otp;
    $subject = "OTP TO RESET PASSWORD.";
    try {
      $this->mailer->sendMail($address, $subject, $body);
      setcookie("otp", base64_encode($otp),15/1440,"/");
      return "* OTP sent in your registered mail Id successfully";
    }
    catch (Exception $e) {
      return "* Invalid Mail";
    }
  }

  /**
   * This is to verify the otp which is entered by the user is correct or not.
   * If the otp is correct then unset the cookie variables and send the message
   * whether the otp is correct or not.
   *
   *   @param string $otp
   *     Accepts the otp entered by the user which is need to be matched with the
   *     previously sent otp.
   *
   * @return string
   *  Returns the message whether the otp is correct or not.
   */
  public function verifyOtp(string $otp): string
  {
    if ($otp == base64_decode($_COOKIE["otp"])) {
      setcookie("otp","",0,"/");
      setcookie("emailId","",0,"/");
      return "* correct otp";
    }
    return "* incorrect otp";
  }
}
?>

