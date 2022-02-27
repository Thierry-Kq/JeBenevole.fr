<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsDeletedTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\AssociationsRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssociationsRepository::class)
 */
class Associations
{
    use IsDeletedTrait;
    use IdTrait;
    use EmailTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;
    use SlugTrait;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $zip;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $fixNumber = null;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $cellNumber = null;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $faxNumber = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isBanned = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActived = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $picture = null;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private ?string $webSite = null;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private ?string $facebook = null;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private ?string $linkedin = null;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private ?string $youtube = null;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private ?string $twitter = null;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="associations")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Offers::class, mappedBy="associations")
     */
    private Collection $offers;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->offers = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getFixNumber(): ?string
    {
        return $this->fixNumber;
    }

    public function setFixNumber(?string $fixNumber): self
    {
        $this->fixNumber = $fixNumber;

        return $this;
    }

    public function getCellNumber(): ?string
    {
        return $this->cellNumber;
    }

    public function setCellNumber(?string $cellNumber): self
    {
        $this->cellNumber = $cellNumber;

        return $this;
    }

    public function getFaxNumber(): ?string
    {
        return $this->faxNumber;
    }

    public function setFaxNumber(?string $faxNumber): self
    {
        $this->faxNumber = $faxNumber;

        return $this;
    }

    public function getIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

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

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): self
    {
        $this->webSite = $webSite;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection|Offers[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offers $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setAssociations($this);
        }

        return $this;
    }

    public function removeOffer(Offers $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getAssociations() === $this) {
                $offer->setAssociations(null);
            }
        }

        return $this;
    }
}
