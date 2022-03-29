<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profil", name="show_profile")
     */
    public function display(): Response {

        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('profile/show.html.twig');
    }

    /**
     * @Route("/profil/modification", name="edit_profile")
     */
    public function edit(
        Request $request,
        UploadService $uploadService,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        $userOldPicture = $user->getPicture();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('picture')->getData();
            if ($imageChange != null){
                $uploadService->deleteImage($userOldPicture, 'users');
                $user->setPicture($uploadService->uploadImage($imageChange, 'users'));
            }

            $user = $form->getData();
            $em->flush();
            $this->addFlash('success', 'success_msg');

            return $this->redirectToRoute('show_profile', ['slug' => $user->getSlug()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
