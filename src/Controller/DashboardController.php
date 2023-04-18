<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Posts;
use App\Entity\PostData;
use App\Services\Registration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * DashboardController, this controller is to manage the the services after user
 * successfully logged in. The main services provided by this controller are
 * fetch post data, add post, update user's profile, view other user's profile.
 *
 * @author rajdip <rajdip.roy@innoraft.com>
 */
class DashboardController extends AbstractController
{
  /**
   * This is a object of EntityManagerInterface class
   * It is to manage persistance and retriveal Entity object from Database.
   *
   *   @var object
   */
  public $em;

  /**
   * It stores a object of User Class to set and get data from database
   * through doctrine.
   *
   *   @var object
   */
  public $user;

  /**
   * It stores a object of PostData Class to set and get data from database
   * through doctrine.
   *
   *   @var object
   */
  public $post;

  /**
   * It stores a object of PostDataRepository class, it is to fetch data
   * from user table of database.
   *
   *   @var object
   */
  public $postRepo;

  /**
   * It stores a object of UserRepository class, it is to fetch data
   * from user table of database.
   *
   *   @var object
   */
  public $userRepo;

  /**
   * It stores the userId of the user who currently logged in and it is fetched
   * from cookie.
   *
   *   @var string
   */
  public $userId;

  /**
   * It stores a object of Posts Class to provide the services required at the
   * time of retrieve the post and add new post.
   *
   *   @var object
   */
  public $postService;

  /**
   * This constructor initializes object of DashboardController Class also
   * provides access to EntityManagerInterface object .
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
    $this->post = new PostData();
    $this->postService = new Posts();
    $this->userRepo = $this->em->getRepository(User::class);
    $this->postRepo = $this->em->getRepository(PostData::class);
    if (isset($_COOKIE["userId"])) {
      $this->userId = $_COOKIE["userId"];
    }
  }

  /**
   * This method is to show the posts of all users, the lists of other
   * users and the information of user,who logged in currently.Basically it
   * takes the user to the dashboard page after login succesfully.
   *
   * @Route("/dashboard", name = "dashboard")
   *  It takes the user to the dashboard page after login succesfully and it
   *  redirects to login page if user try to reach this page without login.
   *
   *   @param object $rq
   *     This Request object is to handles the user credentials.
   *
   *   @return Response
   *     Based on the validation the response is to either dashboard page or
   *     the login page with the proper error messages.
   */
  public function index(): Response
  {
    if (isset($_COOKIE["active"])) {
      return $this->render("dashboard.html.twig",[
        "allpost" => $this->postRepo->findAll(),
        "userInfo" => $this->userRepo->findOneBy(["userId" => $this->userId]),
        "otherUserInfo" => $this->userRepo->findAllByUserIdExcept($this->userId),
      ]);
    }
    return $this->redirect("/");
  }

  /**
   * This method used when user try add a new post, it stores the post data
   * in database and redirects to dashboard with loading the new post.
   *
   * @Route("/makepost", name = "makepost")
   *  This route does not take user to a new page it redirects user to the
   *  dashboard after store the new post data.
   *
   *   @param object $rq
   *     This Request object is to handles the post data.
   *
   *   @return Response
   *     Redirects to the dashboard page.
   */
  public function makePost(Request $rq): Response
  {
    echo $this->userId;
    if ($rq != NULL ) {
      $this->post->setPostId($this->postService->generatePostId($this->userId));
      $this->post->setPostContent($rq->request->get("postContent"));
      $image = $rq->files->get("postImage");
      if ($image != NULL) {
        $this->post->setPostImage($this->postService->postImgStoring($image));
      }
      $this->user = $this->em->getRepository(User::class)->findOneBy([
        "userId" => $this->userId,
      ]);
      $this->post->setPostAuthor($this->user);
      $this->em->persist($this->post);
      $this->em->persist($this->user);
      $this->em->flush();
    }
    return $this->redirect("/dashboard");
  }

  /**
   * This method is to fetch the posts and details of the perticular user whose
   * profile the current user want to visit.
   *
   * @Route("/user", name = "othersprofile")
   *  This route is to take user to the others user profile page after
   *  fetching all the related data.
   *
   *   @param object $rq
   *     This Request object is to take the user id of the user whose
   *     profile the current user want to visit.
   *
   *   @return Response
   *     Takes user to the others user profile page.
   */
  public function user(Request $rq): Response
  {
    $otherUser = $this->userRepo->findOneBy([
      "id" => $rq->query->get("id"),
    ]);
    $otherUserPost = $this->postRepo->findAllByPostAuthor($rq->query->get("id"));
    $user = $this->userRepo->findOneBy([
      "userId" => $this->userId,
    ]);
    return $this->render("othersprofile.html.twig",[
      "otherUser" => $otherUser,
      "userInfo" => $user,
      "otherUserPost" => $otherUserPost,
    ]);
  }

  /**
   * This method is to show the user profile page and made the changes in
   * database if user updates any information about himself.
   *
   * @Route("/profile", name = "profile")
   *
   *    @param object $rq
   *     This Request object is to take the updated user information data .
   *
   *   @return Response
   *     Takes user to the user profile page.
   */
  public function profile(Request $rq): Response
  {
    $user = $this->userRepo->findOneBy([
      "userId" => $this->userId,
    ]);
    if ($rq->request->all() != NULL && md5($rq->request->get("password")) == $user->getPassword()) {
      $user->setFName($rq->request->get('fName'));
      $user->setLName($rq->request->get('lName'));
      $user->setEmailId($rq->request->get('emailId'));
      $user->setBio($rq->request->get('bio'));
      if($rq->files->get("imgUpload") != NULL){
        $registration = new Registration($this->em);
        $user->setProfilePic($registration->imgStoring($rq->files->get("imgUpload"),$this->userId));
      }
      $this->em->persist($user);
      $this->em->flush();
    }
    return $this->render("profile.html.twig", [
      "userInfo" => $user,
    ]);
  }
}
