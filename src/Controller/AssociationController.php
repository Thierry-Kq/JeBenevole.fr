<?php

namespace App\Controller;

use App\Entity\Associations;
use App\Form\AssociationType;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $page = $request->query->getInt('page', 1);
        if ($page <= 0) {
            $page = 1;
            $paginator = $repository->findAllAssociations($page, 3);
        } else {
            $paginator = $repository->findAllAssociations($page, 3);
            $paginator = empty($paginator['items']) ? $repository->findAllAssociations(1, 3) : $paginator;
        }
        return $this->render('association/list.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * @Route("/associations/modification/{slug}", name="edit_association")
     */
    public function edit(Request $request, Associations $association, EntityManagerInterface $em, SluggerInterface $slugger, UploadService $uploadService): Response
    {
        if ($association->getIsDeleted()) {
            throw new HttpException('410');
        }

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
        if ($association->getIsDeleted()) {
            throw new HttpException('410');
        }

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
        if ($association->getIsDeleted()) {
            throw new HttpException('410');
        }

        return $this->render('association/show.html.twig', [
            'association' => $association
        ]);
    }
}
