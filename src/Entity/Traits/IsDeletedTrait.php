<?php

namespace App\Entity\Traits;

trait IsDeletedTrait
{
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDeleted = false;

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
