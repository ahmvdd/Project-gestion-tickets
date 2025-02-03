<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ticket:read'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'text')]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?string $status = "nouveau"; // Valeur par défaut

    #[ORM\Column]
    #[Groups(['ticket:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['ticket:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?User $student = null; // L'élève qui a créé le ticket

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?Category $category = null; // Catégorie choisie par l'élève

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?User $assignedTo = null; // Administrateur assigné au ticket

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private ?string $messageRefus = null; // Message de refus (si applicable)

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->status = "nouveau";
    }

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }

    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }
    public function setUpdatedAt(\DateTime $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }

    public function getStudent(): ?User { return $this->student; }
    public function setStudent(?User $student): static { $this->student = $student; return $this; }

    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }

    public function getAssignedTo(): ?User { return $this->assignedTo; }
    public function setAssignedTo(?User $assignedTo): static { $this->assignedTo = $assignedTo; return $this; }

    public function getMessageRefus(): ?string { return $this->messageRefus; }
    public function setMessageRefus(?string $messageRefus): static { $this->messageRefus = $messageRefus; return $this; }
}
