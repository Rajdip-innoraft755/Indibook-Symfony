<?php
namespace App\Services;

/**
 * Posts class is for help the DashboardController to make new post
 * and retrieve post data.
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class Posts
{
  /**
   * This is to store the post id of a perticular post which stores
   * the some informations about the post i.e post author's user id
   * and time of the post.
   *
   *   @var string
   */
  public $postId;

  /**
   * This method is to generate a unique id of each post i.e postId,
   * which stores some informations about the post.
   *
   *   @param string $userId
   *     Accepts the user id of the post author.
   *
   *   @return string
   *     Returns the newly generated post id of the post.
   */
  public function generatePostId(string $userId): string
  {
    $this->postId = date("Y-m-d H:i:s", time()) . $userId;
    return $this->postId;
  }

  /**
   * This method is to store the image which is posted by the user and
   * send the filepath to the .
   *
   *   @param object $image
   *     Accepts the image posted by user as parameter to store it.
   *
   *   @return string
   *     Returns the filepath of the stored image.
   */
  public function postImgStoring(object $image): string
  {
    $targetFile = $this->postId . $image->getClientOriginalName();
    $image->move("assets/img/", $targetFile);
    return "/assets/img/" . $targetFile;
  }
}
?>
