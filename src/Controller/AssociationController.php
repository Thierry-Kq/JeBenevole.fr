<?php

namespace App\Controller;

use App\Entity\Associations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssociationController extends AbstractController
{
    /**
     * @Route("/association/modification/{slug}", name="edit_association")
     */
    public function edit(Associations $association, string $slug): Response
    {

        
        
        return $this->render('association/edit.html.twig', [
            'controller_name' => 'AssociationController',
        ]);
    }

    /**
     * @Route("/association/suppression/{slug}", name="delete_association", methods="DELETE")
     */
    // public function delete(): Response
    // {
    
    // }

    /**
     * @Route("/association/{slug}", name="show_association")
     */
    public function show(Associations $association, string $slug): Response
    {
        

        return $this->render('association/show.html.twig', [
            'controller_name' => 'AssociationController',
            'association' => $association,
        ]);
    }
}
