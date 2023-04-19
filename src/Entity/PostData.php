<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\PostDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostDataRepository::class)]
class PostData
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $postId = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $postContent = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $postImage = null;

  #[ORM\ManyToOne(inversedBy: 'postData')]
  #[ORM\JoinColumn(nullable: false)]
  private ?user $postAuthor = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getPostId(): ?string
  {
    return $this->postId;
  }

  public function setPostId(string $postId): self
  {
    $this->postId = $postId;

    return $this;
  }

  public function getPostContent(): ?string
  {
    return $this->postContent;
  }

  public function setPostContent(?string $postContent): self
  {
    $this->postContent = $postContent;

    return $this;
  }

  public function getPostImage(): ?string
  {
    return $this->postImage;
  }

  public function setPostImage(?string $postImage): self
  {
    $this->postImage = $postImage;

    return $this;
  }

  public function getPostAuthor(): ?user
  {
    return $this->postAuthor;
  }

  public function setPostAuthor(?user $postAuthor): self
  {
    $this->postAuthor = $postAuthor;

    return $this;
  }
}
