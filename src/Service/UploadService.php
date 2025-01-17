<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    public const ASSOCIATIONS_FOLDER_NAME = 'associations';
    public const OFFERS_FOLDER_NAME = 'offers';
    public const USERS_FOLDER_NAME = 'users';
    public const CATEGORIES_FOLDER_NAME = 'categories';
    public const DIRECTORY_FOLDER_NAME = 'images_directory';

    private ParameterBagInterface $params;
    private string $path = '';

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->path = $this->params->get(self::DIRECTORY_FOLDER_NAME);
    }

    /**
     * Usefull to upload an image file
     *
     * @param UploadedFile $image Name of the image file you want to upload with extension.
     * @param string  $folder Path to the folder where you want to save the image file.
     */
    public function uploadImage(UploadedFile $image, string $folder): string
    {
        $imageName = md5(uniqid()) . '.' . $image->guessExtension();
        $image->move($this->path . $folder . '/', $imageName);

        return $imageName;
    }

    /**
     * Usefull to delete an image file from a folder
     *
     * @param string $image Name of the image file you want to delete.
     * @param string  $folder Path to the folder where you want to save the image file.
     */
    public function deleteImage(?string $image, string $folder): void
    {
        $fileSystem = new Filesystem();
        $fileExist = $fileSystem->exists($this->path . $folder . '/' . $image);

        if ($image != null && $fileExist) {
            unlink($this->path.$folder . '/' . $image);
        }
    }
}
