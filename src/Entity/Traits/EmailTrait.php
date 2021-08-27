<?php

namespace App\Entity\Traits;

trait EmailTrait
{
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
