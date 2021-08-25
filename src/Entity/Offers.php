<?php

namespace App\Entity;

use App\Repository\OffersRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OffersRepository::class)
 */
class Offers
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
    private string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $zip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt = null;

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
     * @ORM\Column(type="boolean")
     */
    private bool $isDeleted = false;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $status = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $dateEnd;

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
    private Users $users;

    /**
     * @ORM\ManyToOne(targetEntity=Associations::class, inversedBy="offers")
     */
    private Associations $associations;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="offers")
     */
    private Categories $categories;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

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

    public function setDateStart(DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(DateTimeInterface $dateEnd): self
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
}
