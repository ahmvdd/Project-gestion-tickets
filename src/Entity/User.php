<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $firstname = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $lastname = null;

    #[Assert\Email]
    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $role = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\OneToMany(targetEntity: Category::class, mappedBy: 'assigned_to')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setAssignedTo($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getAssignedTo() === $this) {
                $category->setAssignedTo(null);
            }
        }

        return $this;
    }
}


