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
     * @Route("/association/creation", name="new_association")
     */
    public function create(Request $request): Response
    {

        $association = new Associations();

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($association);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('show_association');
        }

        return $this->render('association/create.html.twig', [
            'controller_name' => 'AssociationController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/associations", name="associations")
     */
    public function list(): Response
    {

        $associations = $this->getDoctrine()->getRepository(Associations::class)->findAllAssociations();

        return $this->render('association/list.html.twig', [
            'controller_name' => 'AssociationController',
            'associations' => $associations,
        ]);
    }

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
        return $this->redirectToRoute('associations'); // In futur this should redirect user to homepage
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
