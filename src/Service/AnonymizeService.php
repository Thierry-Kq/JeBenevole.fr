<?php

namespace App\Service;

use App\Entity\Associations;
use App\Entity\Categories;
use App\Entity\Offers;
use App\Entity\Users;
use DateTime;

class AnonymizeService
{
    private const DELETED = 'deleted';
    private const DELETED_ZIP = '00000';
    private const DELETED_NUMBER = '0000000000';
    private const DELETED_MAIL = 'deleted@deleted.del';
    private const DELETED_USER_MAIL = 'user@deleted.del';

    private UploadService $uploadService;

    public function __construct(
        UploadService $uploadService
    ) {
        $this->uploadService = $uploadService;
    }

    public function anonymizeAssociation(Associations $association): void
    {
        foreach ($association->getOffers() as $offer) {
            $this->anonymizeOffer($offer);
        }
        $this->uploadService->deleteImage($association->getPicture(), UploadService::ASSOCIATIONS_FOLDER_NAME);

        $association
            ->setIsDeleted(true)
            ->setName(self::DELETED . $association->getId())
            ->setEmail($association->getId() . self::DELETED_MAIL)
            ->setAddress(self::DELETED)
            ->setZip(self::DELETED_ZIP)
            ->setCity(self::DELETED)
            ->setFixNumber(self::DELETED_NUMBER)
            ->setCellNumber(self::DELETED_NUMBER)
            ->setFaxNumber(self::DELETED_NUMBER)
            ->setDescription(self::DELETED)
            ->setWebSite(self::DELETED)
            ->setFacebook(self::DELETED)
            ->setLinkedin(self::DELETED)
            ->setYoutube(self::DELETED)
            ->setTwitter(self::DELETED)
            ->setPicture(null)
            ->setUpdatedAt(new  DateTime());
    }

    public function anonymizeOffer(Offers $offer): void
    {
        $this->uploadService->deleteImage($offer->getFile(), UploadService::OFFERS_FOLDER_NAME);

        $offer
            ->setIsDeleted(true)
            ->setTitle(self::DELETED)
            ->setAddress(self::DELETED)
            ->setZip(self::DELETED_ZIP)
            ->setCity(self::DELETED)
            ->setDescription(self::DELETED)
            ->setContactExternalName(self::DELETED)
            ->setContactExternalEmail(self::DELETED_MAIL)
            ->setContactExternalTel(self::DELETED_NUMBER)
            ->setLatitude(null)
            ->setLongitude(null)
            ->setFile(null)
            ->setUpdatedAt(new  DateTime());
    }

    public function anonymizeUser(Users  $user): void
    {
        foreach ($user->getAssociations()as $association) {
            $this->anonymizeAssociation($association);
        }
        foreach ($user->getOffers() as $offer) {
            $this->anonymizeOffer($offer);
        }
        $this->uploadService->deleteImage($user->getPicture(), UploadService::USERS_FOLDER_NAME);

        $user
            ->setFirstName(self::DELETED)
            ->setLastName(self::DELETED)
            ->setDescription(self::DELETED)
            ->setFixNumber(self::DELETED_NUMBER)
            ->setCellNumber(self::DELETED_NUMBER)
            ->setFacebookId(self::DELETED)
            ->setNickname(self::DELETED)
            ->setIsDeleted(true)
            ->setEmail($user->getId() . self::DELETED_USER_MAIL)
            ->setUpdatedAt(new  DateTime());
    }

    public function anonymizeCategory(Categories  $category): void
    {
        // remove offer ?
    }
}
