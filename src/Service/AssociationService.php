<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AssociationService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function uploadImage($image, $association)
    {
        if ($image) {
            $imageName = md5(uniqid()). '.' .$image->guessExtension();
            $image->move($this->params->get('images_directory'), $imageName);
            $association->setPicture($imageName);
        };
    }
}