<?php
namespace App\Services;

use App\Entity\User;
use App\Services\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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
   * This is a object of EntityManagerInterface class
   * It is to manage persistance and retriveal Entity object from Database.
   *
   *   @var object
   */
  public $em;

  /**
   * This is a object of EntityRepository class
   * It is to fetch data from user table of database.
   *
   *   @var object
   */
  public $userRepo;

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
   * @param object $em
   * It is to manage persistance and retriveal Entity object from Database.
   *
   * @return void
   * Constructor returns nothing .
   */
  public function __construct(EntityManagerInterface $em)
  {
    session_start();
    $this->em = $em;
    $this->mailer = new Mailer();
    $this->userRepo = $this->em->getRepository(User::class);
  }

  /**
   * This method is used to check whether the user id is valid or not, if
   * exists then store the email id and the user id as session variable.
   *
   *   @param string $userId
   *     Accepts the user id which is needed to be checked.
   *
   *   @return string
   *     Returns the message whether the user id valid or invalid.
   */
  public function checkUser(string $userId): string
  {
    if ($this->userRepo->findOneBy(array("userId" => $userId)) != NULL) {
      $user = $this->userRepo->findOneBy(array("userId" => $userId));
      $_SESSION["emailId"] = $user->getEmailId();
      $_SESSION["userId"] = $userId;
      return "* Valid user ID.";
    }
    return "* Invalid user ID.";
  }

  /**
   * This method is to generate the otp and send the otp to the registered
   * email id of the user and store the otp in a session variable to match it
   * in future.
   *
   *   @return string
   *     Returns the message whether the otp sent successfully or not.
   */
  public function sendOtp(): string
  {
    $_SESSION["otp"] = rand(10000, 99999);
    $address = $_SESSION["emailId"];
    $body = "Dear User ,<br><br> Here is your OTP. Please , don't share it with Others. <br><br> OTP : " . $_SESSION["otp"];
    $subject = "OTP TO RESET PASSWORD.";
    try {
      $this->mailer->sendmail($address, $subject, $body);
      return "* OTP sent in your registered mail Id successfully";
    }
    catch (Exception $e) {
      return "* Invalid Mail";
    }
  }

  /**
   * This is to verify the otp which is entered by the user is correct or not.
   * If the otp is correct then unset the session variables and send the message
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
    if ($otp == $_SESSION["otp"]) {
      unset($_SESSION);
      return "* correct otp";
    }
    return "* incorrect otp";
  }
}
?>

