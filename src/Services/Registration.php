<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Registration class is for validate user data , imagestoring and
 * generate the uniqueId .
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class Registration
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
   * This constructor initializes object of Register Class also provides
   * access to EntityManagerInterface object .
   *
   *   @param  object $em
   *     It is to manage persistance and retriveal Entity object from Database.
   *
   *   @return void
   *     Constructor returns nothing .
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->userRepo = $this->em->getRepository(User::class);
  }

  /**
   *  This method checks the whether the user gives the valid inputs or not
   *  and stores the errors in an array and returned it to the calling method.
   *
   *   @param  array $data
   *     This method accepts user data in form of an array as parameter.
   *
   *   @return array
   *     This method returns the errors in form an array
   */
  public function validate(array $data): array
  {
    //Initialize error array
    $error = [];
    // Checks whether the first name contains only alphabet or not .
    // If first name contains other than alphabets then store the error .
    if (!(preg_match("/^[a-zA-Z ]*$/", $data["fName"]))) {
      $error["fName"] = "* first name only contains alphabet.";
    }
    // Checks whether the last name contains only alphabet or not .
    // If last name contains other than alphabets then store the error .
    if (!(preg_match("/^[a-zA-Z ]*$/", $data["lName"]))) {
      $error["lName"] = "* last name only contains alphabet.";
    }
    // Checks whether the email id is in valid format or not .
    // If email id is not in valid format then store the error .
    if (!(preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $data["emailId"]))) {
      $error["emailId"] = "* not a valid email.";
    }
    // If the user id is not free then store the error.
    $error["userId"] = $this->availableUserId($data["userId"]);
    // Checks whether the password follows the following checkpoints or not more
    // than 8 characters atleast one uppercase and one lowercase and one digit
    // and one special characters(@, $, #, !, %, *, ?, &).
    // If the password does not match these conditions then store the error.
    if (!(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$#!%*?&])[A-Za-z\d@#$!%*?&]{8,}$/", $data["password"]))) {
      $error["password"] = "* weak password.";
    }
    return $error;
  }

  /**
   *  This method is used to store the user profile picture in local storage.
   *  And returns the filepath to store that in database.
   *
   *   @param mixed $image
   *     Accepts the HTTPFoundation files object based on image file input
   *
   *   @param string $userId
   *     Accepts the user id of perticular user.
   *
   *   @return string
   *     Returns the filepath of the stored profile picture.
   */
  public function imgStoring(mixed $image, string $userId)
  {
    if ($image != NULL) {
      $targetFile = $userId . "-profile-pic-" . $image->getClientOriginalName();
      $image->move("assets/img/", $targetFile);
      return "/assets/img/" . $targetFile;
    }
    return "/assets/img/default-dp.jpg";
  }

  /**
   *  This method geneate a unique Id for the user which stores the emailId
   *  and the time of registration.
   *
   *   @param  string $email
   *     Accepts the user email as parameter.
   *
   *   @return string
   *     Returns the generated uniqueId for the user.
   */
  public function generateUniqueId(string $email): string
  {
    return date("Y-m-d H:i:s", time()) . $email;
  }

  /**
   * This method is used to check whether the user id is already exits in
   * database or not.
   *
   *   @param string $userId
   *     Accepts user id as input .
   *
   *   @return string
   *     Returns error message if the user id already taken otherwise empty string.
   */
  public function availableUserId(string $userId): string
  {
    if ($this->userRepo->findBy(array("userId" => $userId)) != NULL) {
      return "* UserId already exists.";
    }
    return "";
  }
}
?>
