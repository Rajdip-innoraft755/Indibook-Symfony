<?php

namespace App\Entity;

use App\Entity\PostData;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $userId = null;

  #[ORM\Column(length: 255)]
  private ?string $uniqueId = null;

  #[ORM\Column(length: 50)]
  private ?string $fName = null;

  #[ORM\Column(length: 50)]
  private ?string $lName = null;

  #[ORM\Column(length: 255)]
  private ?string $emailId = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $profilePic = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $bio = null;

  #[ORM\Column(length: 25, nullable: true)]
  private ?string $cookie = null;

  #[ORM\Column(length: 255)]
  private ?string $password = null;

  #[ORM\OneToMany(mappedBy: 'postAuthor', targetEntity: PostData::class)]
  private Collection $postData;

  public function __construct()
  {
    $this->postData = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getUserId(): ?string
  {
    return $this->userId;
  }

  public function setUserId(string $userId): self
  {
    $this->userId = $userId;

    return $this;
  }

  public function getUniqueId(): ?string
  {
    return $this->uniqueId;
  }

  public function setUniqueId(string $uniqueId): self
  {
    $this->uniqueId = $uniqueId;

    return $this;
  }

  public function getFName(): ?string
  {
    return $this->fName;
  }

  public function setFName(string $fName): self
  {
    $this->fName = $fName;

    return $this;
  }

  public function getLName(): ?string
  {
    return $this->lName;
  }

  public function setLName(string $lName): self
  {
    $this->lName = $lName;

    return $this;
  }

  public function getEmailId(): ?string
  {
    return $this->emailId;
  }

  public function setEmailId(string $emailId): self
  {
    $this->emailId = $emailId;

    return $this;
  }

  public function getProfilePic(): ?string
  {
    return $this->profilePic;
  }

  public function setProfilePic(?string $profilePic): self
  {
    $this->profilePic = $profilePic;

    return $this;
  }

  public function getBio(): ?string
  {
    return $this->bio;
  }

  public function setBio(?string $bio): self
  {
    $this->bio = $bio;

    return $this;
  }

  public function getCookie(): ?string
  {
    return $this->cookie;
  }

  public function setCookie(?string $cookie): self
  {
    $this->cookie = $cookie;

    return $this;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * This method is to set all the user information, user gives at the time of
   * registration. It basically combine all the setter function to make it easy
   * for the user to store the details.
   *
   *   @param  string $userId
   *     It is take the user id as input.
   *
   *   @param  string $uniqueId
   *     It is take the unique id as input.
   *
   *   @param  string $fName
   *     It is take the first name as input.
   *
   *   @param  string $lName
   *     It is take the last name as input.
   *
   *   @param  string $emailId
   *     It is take the email id as input.
   *
   *   @param  string $profilePic
   *     It is take the filepath of the profile picture as input.
   *
   *   @param  string $password
   *     It is take the password as input.
   *
   *   @param  string $cookie
   *     It is take the cookie preferrence as input.
   *
   *   @return void
   *     This function just set the information returns nothing.
   */
  public function setter(string $userId, string $uniqueId, string $fName, string $lName, string $emailId, string $profilePic, string $password, string $cookie)
  {
    $this->setUserId($userId);
    $this->setUniqueId($uniqueId);
    $this->setFName($fName);
    $this->setLname($lName);
    $this->setEmailId($emailId);
    $this->setProfilePic($profilePic);
    $this->setPassword($password);
    $this->setCookie($cookie);
  }

  /**
   * @return Collection<int, PostData>
   */
  public function getPostData(): Collection
  {
    return $this->postData;
  }

  public function addPostData(PostData $postData): self
  {
    if (!$this->postData->contains($postData)) {
      $this->postData->add($postData);
      $postData->setPostAuthor($this);
    }

    return $this;
  }

  public function removePostData(PostData $postData): self
  {
    if ($this->postData->removeElement($postData)) {
      // set the owning side to null (unless already changed)
      if ($postData->getPostAuthor() === $this) {
        $postData->setPostAuthor(null);
      }
    }

    return $this;
  }
}
