<?php

namespace App\Service;

use App\Entity\Associations;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AssociationService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function uploadImage(object $image, Associations $association)
    {
            $imageName = md5(uniqid()). '.' .$image->guessExtension();
            $image->move($this->params->get('association_images_directory'), $imageName);
            $association->setPicture($imageName);
    }

    public function deleteImage(string $image)
    {
        if ($image != null) {  
            unlink($this->params->get('association_images_directory'). '/' .$image);
        };
    }
}