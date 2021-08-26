<?php

namespace App\Entity\Traits;

use DateTimeImmutable;

trait CreatedAtTrait
{
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
}
