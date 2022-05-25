<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IdTrait;
use App\Repository\AnonymizationAskedRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnonymizationAskedRepository::class)
 */
class AnonymizationAsked
{
    use IdTrait;
    use CreatedAtTrait;

    /**
     * @ORM\OneToOne(targetEntity=Users::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Users $users;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $anonymizedAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }
    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getAnonymizedAt(): ?DateTimeInterface
    {
        return $this->anonymizedAt;
    }

    public function setAnonymizedAt(?\DateTimeInterface $anonymizedAt): self
    {
        $this->anonymizedAt = $anonymizedAt;

        return $this;
    }
}
