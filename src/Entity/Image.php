<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\UploadImageAction;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *       "formats"={"json", "form"={"multipart/form-data"}}
 *     },
 *     collectionOperations={
 *       "post"={
 *          "method"="POST",
 *          "path"="/images",
 *          "controller"=UploadImageAction::class,
 *          "defaults"={"_api_receive"=false}
 *
 *       }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @Vich\Uploadable()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="images")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
        }

        return $this;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

    }

    public function getFile(): ?File
    {
        return $this->file;
    }
}
