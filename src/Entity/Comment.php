<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={"get", "delete",
 *       "put"={
 *          "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and ((is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user) or is_granted('ROLE_EDITOR'))"
 *        }
 *     },
 *     collectionOperations={
 *        "post"={
 *           "access_control"="is_granted('ROLE_COMMENTATOR') and is_granted('IS_AUTHENTICATED_FULLY')"
 *        }
 *     },
 *     subresourceOperations={
 *     "api_posts_comments_get_subresource"={
 *         "method"="GET",
 *         "normalization_context"={"groups"={"post-comment-with-author"}}
 *     }
 *   }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"post-comment-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post-comment-with-author"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @Groups({"post-comment-with-author"})
     * @ApiSubresource()
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="posts")
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function __toString(): string {
        return substr($this->content, 0, 20) . '...';
    }

}
