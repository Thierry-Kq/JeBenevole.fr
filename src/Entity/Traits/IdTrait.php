<?php

namespace App\Entity\Traits;

trait IdTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
