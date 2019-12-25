<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ApiResource(
 *      itemOperations={"get"},
 *      collectionOperations={"post"},
 * 
 *      normalizationContext={
 *          "groups" = {"read"}
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")

 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read"})
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=30)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Expression(
     *      "this.getPassword() === this.getRetypedPassword()",
     *      message = "Passwords does not match"
     * )
     */
    private $retypedPassword;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read"})
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=120)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="user")
     * @Groups({"read"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     * @Groups({"read"})
     */
    private $comments;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(BlogPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(BlogPost $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
    * Returns the roles granted to the user.
    *
    *     public function getRoles()
    *     {
    *         return ['ROLE_USER'];
    *     }
    *
    * Alternatively, the roles might be stored on a ``roles`` property,
    * and populated in any number of different ways when the user object
    * is created.
    *
    * @return (Role|string)[] The user roles
    */
   public function getRoles(){
       return ['ROLE_USER'];
   }

   /**
    * Returns the salt that was originally used to encode the password.
    *
    * This can return null if the password was not encoded using a salt.
    *
    * @return string|null The salt
    */
   public function getSalt(){
       return null;
   }

   /**
    * Removes sensitive data from the user.
    *
    * This is important if, at any given point, sensitive information like
    * the plain-text password is stored on this object.
    */
   public function eraseCredentials(){
       
   }


   public function getRetypedPassword()
   {
       return $this->retypedPassword;
   }

   public function setRetypedPassword($retypedPassword): void
   {
       $this->retypedPassword = $retypedPassword;
   }

}
