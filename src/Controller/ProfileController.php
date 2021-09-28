<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ProfileFormType;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profil/{slug}", name="show_profile")
     */
    public function display(Users $user){

        if ($user->getIsDeleted()) {
            throw new HttpException('410');
        }

        return $this->render('profile/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profil/modification/{slug}", name="edit_profile")
     */
    public function edit(
        Request $request,
        Users $user,
        UploadService $uploadService,
        EntityManagerInterface $em
    ) {

        if ($user->getIsDeleted()) {
            throw new HttpException('410');
        }

        $this->denyAccessUnlessGranted('edit', $user);

        $userOldPicture = $user->getPicture();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('picture')->getData();
            if ($imageChange != null){
                $uploadService->deleteImage($userOldPicture, 'users');
                $user->setPicture($uploadService->uploadImage($imageChange, 'users'));
            } else {
                $user->setPicture($userOldPicture);
            }

            $user = $form->getData();
            $em->flush();

            return $this->redirectToRoute('show_profile', ['slug' => $user->getSlug()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}