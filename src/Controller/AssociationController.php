<?php

namespace App\Controller;

use App\Entity\Associations;
use App\Form\AssociationType;
use App\Repository\AssociationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AssociationController extends AbstractController
{
    /**
     * @Route("/association/creation", name="new_association")
     */
    public function create(Request $request,EntityManagerInterface $em): Response
    {

        $association = new Associations();

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($association);
            $em->flush();
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
    public function list(AssociationsRepository $repository): Response
    {

        $associations = $repository->findAllAssociations();

        return $this->render('association/list.html.twig', [
            'controller_name' => 'AssociationController',
            'associations' => $associations,
        ]);
    }

    /**
     * @Route("/association/modification/{slug}", name="edit_association")
     */
    public function edit(Request $request, Associations $association, string $slug, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $association = $form->getData();
            $em->flush();
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
    public function delete(Associations $association, string $slug, EntityManagerInterface $em): Response
    {
        $association->setIsDeleted(1);
        $em->flush();
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
