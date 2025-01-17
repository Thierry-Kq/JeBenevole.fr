<?php

namespace App\Controller;

use Exception;
use App\Entity\Associations;
use App\Form\AssociationType;
use App\Service\UploadService;
use App\Service\AnonymizeService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssociationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AssociationController extends AbstractController
{
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
     * @Route("/associations/creation", name="new_association")
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UploadService $uploadService,
        SluggerInterface $slugger,
        AssociationsRepository $associationsRepository,
        TranslatorInterface $translator
    ): Response
    {
        $association = new Associations();

        $this->denyAccessUnlessGranted('create', $association);

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if ($associationsRepository->findOneBy(['email' => $form->get('email')->getData()])) {
                $this->addFlash('warning', $translator->trans('error_msg'));

                return $this->redirectToRoute('new_association');
            }

            $image = $form->get('picture')->getData();
            if ($image != null){
                $association->setPicture($uploadService->uploadImage($image, UploadService::ASSOCIATIONS_FOLDER_NAME));
            }

            $slug = $slugger->slug($association->getName());
            $association->setSlug($slug);
            $association->setUsers($this->getUser());

            $em->persist($association);
            try {
                $em->flush();
                $this->addFlash('success', $translator->trans('success_msg'));
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('error_msg'));

                return $this->redirectToRoute('new_association');
            }

            return $this->redirectToRoute('show_association', ['slug' => $slug]);
        }

        return $this->render('association/create-and-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/associations/modification/{slug}", name="edit_association")
     */
    public function edit(
        Request $request,
        Associations $association,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        UploadService $uploadService,
        AssociationsRepository $associationsRepository,
        TranslatorInterface $translator
    ): Response
    {
        if ($association->getIsDeleted()) {
            throw new HttpException('410');
        }
        $this->denyAccessUnlessGranted('edit', $association);

        $associationOldPicture = $association->getPicture();

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $oldSlug = $association->getSlug();
            if ($associationsRepository->findOneBy(['email' => $form->get('email')->getData()])) {
                $this->addFlash('error', $translator->trans('error_msg'));

                return $this->redirectToRoute('edit_association', ['slug' => $oldSlug]);
            }

            $imageChange = $form->get('picture')->getData();
            if($imageChange != null){
                $uploadService->deleteImage($associationOldPicture, UploadService::ASSOCIATIONS_FOLDER_NAME);
                $association->setPicture($uploadService->uploadImage($imageChange, UploadService::ASSOCIATIONS_FOLDER_NAME));
            }

            $slug = $slugger->slug($association->getName());
            $association->setSlug($slug);

            try {
                $em->flush();
                $this->addFlash('success', $translator->trans('success_msg'));
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('error_msg'));

                return $this->redirectToRoute('edit_association', ['slug' => $oldSlug]);
            }

            return $this->redirectToRoute('show_association', ['slug' => $association->getSlug()]);
        }

        return $this->render('association/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'association' => $association
        ]);
    }

    /**
     * @Route("/associations/anonymisation/{slug}", name="anonymize_association")
     */
    public function anonymize(
        Associations $association,
        EntityManagerInterface $em,
        AnonymizeService $anonymizeService,
        TranslatorInterface $translator
    ): Response {
        if ($association->getIsDeleted()) {
            throw new HttpException('410');
        }
        $this->denyAccessUnlessGranted('anonymize', $association);
        $anonymizeService->anonymizeAssociation($association);
        $em->flush();
        $this->addFlash('success', $translator->trans('success_msg'));
        
        return $this->redirectToRoute('associations');
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
