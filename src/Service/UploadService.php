<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * Usefull to upload an image file
     *
     * @param UploadedFile $image Name of the image file you want to upload with extension.
     * @param string  $folder Path to the folder where you want to save the image file.
     */
    public function uploadImage(UploadedFile $image, string $folder): string
    {
        $imageName = md5(uniqid()). '.' .$image->guessExtension();
        $image->move($this->params->get($folder), $imageName);
        return $imageName;
    }

    /**
     * Usefull to delete an image file from a folder
     *
     * @param string $image Name of the image file you want to delete.
     * @param string  $folder Path to the folder where you want to save the image file (absolute path).
     * @param string  $path Path to the folder where you want to save the image file (relative path start after public/).
     */
    public function deleteImage(?string $image, string $folder, string $path): void
    {
        $fileSystem = new Filesystem();
        $fileExist = $fileSystem->exists($path.'/'.$image);
        if ($image != null && $fileExist) {  
            unlink($this->params->get($folder). '/' .$image);
        };
    }
}