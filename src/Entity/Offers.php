<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\IsDeletedTrait;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\OffersRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffersRepository::class)
 */
class Offers
{
    use IsDeletedTrait;
    use IdTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;
    use SlugTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $zip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $city;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $longitude = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $latitude = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isPublished = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActived = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isUrgent = false;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $status = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $dateEnd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $file = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $recommended = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $contactExternalName = null;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private ?string $contactExternalEmail = null;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $contactExternalTel = null;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="offers")
     */
    private ?Users $users;

    /**
     * @ORM\ManyToOne(targetEntity=Associations::class, inversedBy="offers")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ?Associations $associations;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="offers")
     */
    private Categories $categories;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

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

    public function getIsUrgent(): ?bool
    {
        return $this->isUrgent;
    }

    public function setIsUrgent(bool $isUrgent): self
    {
        $this->isUrgent = $isUrgent;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateStart(): ?DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getRecommended(): ?bool
    {
        return $this->recommended;
    }

    public function setRecommended(bool $recommended): self
    {
        $this->recommended = $recommended;

        return $this;
    }

    public function getContactExternalName(): ?string
    {
        return $this->contactExternalName;
    }

    public function setContactExternalName(?string $contactExternalName): self
    {
        $this->contactExternalName = $contactExternalName;

        return $this;
    }

    public function getContactExternalEmail(): ?string
    {
        return $this->contactExternalEmail;
    }

    public function setContactExternalEmail(?string $contactExternalEmail): self
    {
        $this->contactExternalEmail = $contactExternalEmail;

        return $this;
    }

    public function getContactExternalTel(): ?string
    {
        return $this->contactExternalTel;
    }

    public function setContactExternalTel(?string $contactExternalTel): self
    {
        $this->contactExternalTel = $contactExternalTel;

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

    public function getAssociations(): ?Associations
    {
        return $this->associations;
    }

    public function setAssociations(?Associations $associations): self
    {
        $this->associations = $associations;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function isARequest(): bool
    {
        return $this->getUsers() ? true : false;
    }
}
