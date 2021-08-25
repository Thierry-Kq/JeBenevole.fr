<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActived = false;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $picture;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private string $color;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id")
     */
    private Categories $parentId;

    /**
     * @ORM\OneToMany(targetEntity=Categories::class, mappedBy="parentId")
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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
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

    public function getParentId(): ?self
    {
        return $this->parentId;
    }

    public function setParentId(?self $parentId): self
    {
        $this->parentId = $parentId;

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
            $child->setParentId($this);
        }

        return $this;
    }

    public function removeChild(Categories $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParentId() === $this) {
                $child->setParentId(null);
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
}
