<?php

namespace App\Controller;

use App\Entity\Associations;
use App\Form\AssociationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssociationController extends AbstractController
{
    /**
     * @Route("/association/modification/{slug}", name="edit_association")
     */
    public function edit(Request $request, Associations $association, string $slug): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        return $this->render('association/edit.html.twig', [
            'controller_name' => 'AssociationController',
            'form' => $form->createView(),
            'association' => $association,
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
