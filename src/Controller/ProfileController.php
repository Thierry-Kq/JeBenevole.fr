<?php

namespace App\Controller;

use App\Entity\AnonymizationAsked;
use App\Form\ProfileFormType;
use App\Repository\AnonymizationAskedRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profil", name="show_profile")
     */
    public function display(): Response {

        return $this->render('profile/show.html.twig');
    }

    /**
     * @Route("/profil/modification", name="edit_profile")
     */
    public function edit(
        Request $request,
        UploadService $uploadService,
        EntityManagerInterface $em,
        TranslatorInterface $translator
    ): Response {
        $user = $this->getUser();

        $userOldPicture = $user->getPicture();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('picture')->getData();
            if ($imageChange != null){
                $uploadService->deleteImage($userOldPicture, UploadService::USERS_FOLDER_NAME);
                $user->setPicture($uploadService->uploadImage($imageChange, UploadService::USERS_FOLDER_NAME));
            }

            $user = $form->getData();
            $em->flush();
            $this->addFlash('success', $translator->trans('success_msg'));

            return $this->redirectToRoute('show_profile', ['slug' => $user->getSlug()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/anonymization", name="anonymize_profile")
     */
    public function anonymize(
        Request $request,
        UploadService $uploadService,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        AnonymizationAskedRepository $anonymizationAskedRepository
    ): Response {
        $user = $this->getUser();
        $alreadyAsked = $anonymizationAskedRepository->findOneBy(['users' => $user]);
        if (!$alreadyAsked) {
            $anonymizationAsked = new AnonymizationAsked();
            $anonymizationAsked->setUsers($user);
            $em->persist($anonymizationAsked);
            $em->flush();
            $this->addFlash('success', 'ok');
        } else {
            $this->addFlash('warning', 'already');
        }

        return $this->redirectToRoute('show_profile');
    }
    /**
     *  ici action de demande d'anonymize
     *  bouton + modal, confirmation fait un appel en ajax, on verifie le crsf +
     *  a voir comment on procede, on a un bouton, qui ouvre une modal, si on confirm, creation de la table, flashmessage ?
     */
}
