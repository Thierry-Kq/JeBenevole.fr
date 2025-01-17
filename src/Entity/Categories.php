<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsDeletedTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\CategoriesRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    use IsDeletedTrait;
    use IdTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;
    use SlugTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActived = false;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $picture;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private ?string $color;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="children")
     */
    private ?Categories $parent;

    /**
     * @ORM\OneToMany(targetEntity=Categories::class, mappedBy="parent")
     */
    private Collection $children;

    /**
     * @ORM\OneToMany(targetEntity=Offers::class, mappedBy="categories")
     */
    private Collection $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->children = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActived(): ?bool
    {
        return $this->isActived;
    }

    public function setIsActived(bool $isActived): self
    {
        $this->isActived = $isActived;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|Offers[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffers(Offers $offers): self
    {
        if (!$this->offers->contains($offers)) {
            $this->offers[] = $offers;
            $offers->setCategories($this);
        }

        return $this;
    }

    public function removeOffers(Offers $offers): self
    {
        if ($this->offers->removeElement($offers)) {
            // set the owning side to null (unless already changed)
            if ($offers->getCategories() === $this) {
                $offers->setCategories(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Categories $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Categories $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function addOffer(Offers $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setCategories($this);
        }

        return $this;
    }

    public function removeOffer(Offers $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getCategories() === $this) {
                $offer->setCategories(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
