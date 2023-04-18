<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Registration;
use App\Services\ForgotPassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * HomeController, this controller is to manage the register user through
 * Registration class  and handles the login process also , On sucessful login
 * this controller redirects to DashboardController.
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class HomeController extends AbstractController
{
  /**
   * It stores a object of EntityManagerInterface class
   * It is to manage persistance and retriveal Entity object from Database.
   *
   *   @var object
   */
  public $em;

  /**
   * It stores a object of UserRepository class, it is to fetch data
   * from user table of database.
   *
   *   @var object
   */
  public $userRepo;

  /**
   * It stores a object of User Class to set and get data from database
   * through doctrine.
   *
   *   @var object
   */
  public $user;

  /**
   * It stores a object of Registration Class to validate the user input data
   * at the time of registration.
   *
   *   @var object
   */
  public $registration;

  /**
   * It stores a object of ForgotPassword Class to validate user id, send otp,
   * verify otp.
   *
   *   @var object
   */
  public $forgotPassword;

  /**
   *
   * This constructor initializes object of HomeController Class also provides
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
    $this->user = new User();
    $this->registration = new Registration($this->em);
    $this->forgotPassword = new ForgotPassword($this->em);
    $this->userRepo = $this->em->getRepository(User::class);
  }

  /**
   * This method to take user credentials and checks in database whether those
   * are valid or not , if the credentials are valid then send the user to the
   * dashboard page otherwise return to the login page with error message.
   *
   * @Route("/")
   *  This route take user to the first page of website and then send user to
   *  dashboard based on given credentials.
   *
   *   @param object $rq
   *     This Request object is to handles the user credentials.
   *
   *   @return Response
   *     Based on the validation the response is to either dashboard page or
   *     the login page with the proper error messages.
   */
  public function login(Request $rq): Response
  {
    $data = $rq->request->all();
    if ($data != NULL) {
      $user = $this->userRepo->findOneBy([
        "userId" => $data["userId"],
        "password" => md5($data["password"])
      ]);
      if ($user != NULL) {
        setcookie("userId", $data["userId"]);
        setcookie("active", TRUE);
        return $this->redirect("/dashboard");
      } else {
        return $this->render("login.html.twig",
        [ "loginErr" => "* Invalid Credentials ." ]);
      }
    }
    return $this->render("login.html.twig");
  }
  /**
   * This method to take user input data and validate those data and
   * store the user data in database if the all the data are valid and send
   * the user to the login page otherwise
   * return to the registration page with the error .
   *
   * @Route("/register", name="register")
   *  This route to take user to the registration page and then send to
   *  login page based on input data validation.
   *
   *   @param object $rq
   *     This Request object is to handles the user input data.
   *
   *   @return Response
   *     Based on the validation the response is to either login page or
   *     the restration page with the proper error messages.
   */
  public function register(Request $rq): Response
  {
    $data = $rq->request->all();
    if ($data != NULL) {
      $image = $rq->files->get('imgUpload');
      $error = $this->registration->validate($data);
      $filepath = $this->registration->imgStoring($image, $data["userId"]);
      if ($error == NULL && $filepath != FALSE) {
        $uniqueId = $this->registration->generateUniqueId($data["emailId"]);
        $cookie = !empty($data["cookie"]) ? "accept" : "decline";
        $this->user->setter($data["userId"], $uniqueId, $data["fName"], $data["lName"], $data["emailId"], $filepath, md5($data["password"]), $cookie);
        $this->em->persist($this->user);
        $this->em->flush();
        return $this->redirect("/");
      }
      return $this->render("register.html.twig", array("error" => $error));
    }
    return $this->render("register.html.twig");
  }

  /**
   * This method destroys the cookies which are set at the time of login.
   *
   * @Route("/logout", name = "logout")
   *  This route logout the user and returns to the login page.
   *
   *   @return Response
   *     Returns the response to login page.
   */
  public function logout(): Response
  {
    setcookie("active", "", 0);
    setcookie("userId", "", 0);
    return $this->redirect("/");
  }

  /**
   * This method used in ajax call of from the registration page when user
   * enters the user id field then it checks whether the userId is available
   * or not.
   *
   * @Route("/validuserid", name="availableuserid")
   *  This route used to send message to the ajax function if user id
   *  is already used.
   *
   *   @param object $rq
   *     This Request object is to handles the user input data.
   *
   *   @return JsonResponse
   *     Returns Json data to the calling ajax function.
   */
  public function validUserId(Request $rq): JsonResponse
  {
    $error = $this->registration->availableUserId($rq->get("userId"));
    return new JsonResponse(json_encode(["isAvialableUserId" => $error]));
  }

  /**
   * This method is the render the forgot password page on
   * which the user can reset his/her password.
   *
   * @Route("/forgotpassword", name = "forgotPassword")
   *  THis route to take the user to the forgot password page.
   *
   *   @return Response
   *     Returns Response to the forgot password page.
   */
  public function forgotPassword(Request $rq): Response
  {
    return $this->render("forgotpassword.html.twig");
  }

  /**
   * This method is used for verify the user id entered by the user.
   *
   * @Route("/forgotpassword/checkuserid")
   *  This route used to send message to the ajax function if user id
   *  is valid or not.
   *
   *   @param Request $rq
   *     Accepts the Request from the ajax call.
   *
   *   @return JsonResponse
   *     Returns Json data to the calling ajax function.
   */
  public function verifyUser(Request $rq): JsonResponse
  {
   $message = $this->forgotPassword->checkUser($rq->request->get("userId"));
   return new JsonResponse(json_encode([
    "isValidUser" => $message,
   ]));
  }

  /**
   * This method is to send the otp to the user email id and returns
   * the success message to the user.
   *
   * @Route("/forgotpassword/sendotp")
   *  This route used to send message to the ajax function whether the mail sent
   *  successfully or not.
   *
   *   @return JsonResponse
   *     Returns Json data to the calling ajax function.
   */
  public function sendOtp(): JsonResponse
  {
    $message = $this->forgotPassword->sendOtp();
    return new JsonResponse(json_encode([
      "otpSendMessage" => $message,
    ]));
  }

  /**
   * This method verify the otp entered by the user and returns the
   * message accordingly.
   *
   * @Route("/forgotpassword/verifyotp")
   *  This route used to send message to the ajax function whether the
   *  otp is correct or not.
   *
   *   @param Request $rq
   *     Accepts the Request from the ajax call.
   *
   *   @return JsonResponse
   *     Returns Json data to the calling ajax function.
   */
  public function verifyOtp(Request $rq): JsonResponse
  {
    $message = $this->forgotPassword->verifyOtp($rq->request->get('otp'));
    return new JsonResponse(json_encode([
      "verifyOtp" => $message,
    ]));
  }

  /**
   * This method is to reset the password of the user basically it stores
   * the new password entered by the user in database of that perticular user
   * and send a success message.
   *
   * @Route("/forgotpassword/reset")
   *  This route used to reset the password send message to the ajax
   *  function that the password reset successfully.
   *
   *   @param Request $rq
   *     Accepts the Request from the ajax call.
   *
   *   @return JsonResponse
   *     Returns Json data to the calling ajax function.
   */
  public function resetPassword(Request $rq): JsonResponse
  {
    $user = $this->userRepo->findOneBy([
      "userId" => $rq->request->get("userid"),
    ]);
    $user->setPassword(md5($rq->request->get("password")));
    $this->em->persist($user);
    $this->em->flush();
    return new JsonResponse(json_encode([
      "resetPassword" =>
      "Password changed successfully please login with your new password",
    ]));
  }
}
?>
