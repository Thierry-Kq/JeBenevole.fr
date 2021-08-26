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

        if($form->isSubmitted() && $form->isValid())
        {
            $association = $form->getData();
            $this->getDoctrine()->getManager()->flush();
        };

        return $this->render('association/edit.html.twig', [
            'controller_name' => 'AssociationController',
            'form' => $form->createView(),
            'association' => $association,
        ]);
    }

    /**
     * @Route("/association/suppression/{slug}", name="delete_association")
     */
    public function delete(Associations $association, string $slug): Response
    {
        $association->setIsDeleted(1);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('homepage');
    }

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
