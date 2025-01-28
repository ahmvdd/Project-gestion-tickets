<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Groups(['category:read', 'category:write', 'user:read'])]
    private ?string $nameCategory = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[Groups(['category:read', 'category:write'])]
    private ?User $assigned_to = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategory(): ?string
    {
        return $this->nameCategory;
    }

    public function setNameCategory(string $nameCategory): static
    {
        $this->nameCategory = $nameCategory;

        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assigned_to;
    }

    public function setAssignedTo(?User $assigned_to): static
    {
        $this->assigned_to = $assigned_to;

        return $this;
    }
}
