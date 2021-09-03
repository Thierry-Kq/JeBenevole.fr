<?php

namespace App\Controller;

use App\Entity\Associations;
use App\Form\AssociationType;
use App\Repository\AssociationsRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class AssociationController extends AbstractController
{
    /**
     * @Route("/associations/creation", name="new_association")
     */
    public function create(Request $request, EntityManagerInterface $em, UploadService $uploadService, SluggerInterface $slugger): Response
    {

        $association = new Associations();

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $image = $form->get('picture')->getData();
            if ($image != null){
                $association->setPicture($uploadService->uploadImage($image, 'associations'));
            }
            
            $slug = $slugger->slug($association->getName());
            $association->setSlug($slug);

            $em->persist($association);
            $em->flush();
            return $this->redirectToRoute('show_association', ['slug' => $slug]);
        }

        return $this->render('association/create-and-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/associations", name="associations")
     */
    public function list(Request $request, AssociationsRepository $repository): Response
    {    
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $repository->findAllAssociations($offset);

        return $this->render('association/list.html.twig', [
            'associations' => $paginator,
            'previous' => $offset - AssociationsRepository::PAGINATOR_PER_PAGE,
            'current_page' => ($offset/AssociationsRepository::PAGINATOR_PER_PAGE) +1,
            'previous_page' => $offset/AssociationsRepository::PAGINATOR_PER_PAGE,
            'next_page' => ($offset/AssociationsRepository::PAGINATOR_PER_PAGE)+2,
            'next' => min(count($paginator), $offset + AssociationsRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    /**
     * @Route("/associations/modification/{slug}", name="edit_association")
     */
    public function edit(Request $request, Associations $association, EntityManagerInterface $em, SluggerInterface $slugger, UploadService $uploadService): Response
    {
        $associationOldPicture = $association->getPicture();

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('picture')->getData();
            if($imageChange != null){
                $uploadService->deleteImage($associationOldPicture, 'associations');
                $association->setPicture($uploadService->uploadImage($imageChange, 'associations'));
            }else{
                $association->setPicture($associationOldPicture);
            }

            $association = $form->getData();
            $slug = $slugger->slug($association->getName());
            $association->setSlug($slug);
            $em->flush();

            return $this->redirectToRoute('show_association', ['slug' => $association->getSlug()]);
        }

        return $this->render('association/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'association' => $association
        ]);
    }

    /**
     * @Route("/associations/suppression/{slug}", name="delete_association")
     */
    public function delete(Associations $association, EntityManagerInterface $em): Response
    {
        $association->setIsDeleted(1);

        $association->setName('deleted');
        $association->setEmail('deleted' . $association->getId() . '@deleted.del');
        $association->setAddress('deleted');
        $association->setZip('00000');
        $association->setCity('deleted');
        $association->setFixNumber(0000000000);
        $association->setCellNumber(0000000000);
        $association->setFaxNumber(0000000000);
        $association->setDescription('deleted');
        $association->setWebSite('deleted');
        $association->setFacebook('deleted');
        $association->setLinkedin('deleted');
        $association->setYoutube('deleted');
        $association->setTwitter('deleted');

        $em->flush();

        return $this->redirectToRoute('associations'); // In futur this should redirect user to homepage
    }

    /**
     * @Route("/associations/{slug}", name="show_association")
     */
    public function show(Associations $association): Response
    {

        return $this->render('association/show.html.twig', [
            'association' => $association
        ]);
    }
}
