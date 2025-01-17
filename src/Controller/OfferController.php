<?php

namespace App\Controller;

use Exception;
use App\Entity\Offers;
use App\Form\OfferType;
use App\Service\UploadService;
use App\Service\AnonymizeService;
use App\Repository\OffersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
     /**
     * @Route("/offres", name="offers")
     * @Route("/demandes", name="requests")
     */
    public function list(Request $request, OffersRepository $offersRepository): Response
    {
        $route = $request->get('_route');

        $page = $request->query->getInt('page', 1);
        if ($page <= 0) {
            $page = 1;
            $paginator = $offersRepository->findAllOffersOrRequests($page, $route, 3);
        } else {
            $paginator = $offersRepository->findAllOffersOrRequests($page, $route, 3);
            $paginator = empty($paginator['items']) ? $offersRepository->findAllOffersOrRequests(1, $route, 3) : $paginator;
        }

        return $this->render('offer/list.html.twig', [
            'paginator' => $paginator,
            'route' => $route
        ]);
    }

    /**
     * @Route("/offres/creation", name="new_offer")
     * @Route("/demandes/creation", name="new_request")
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UploadService $uploadService,
        SluggerInterface $slugger,
        TranslatorInterface $translator
    ): Response
    {
        $offers = new Offers();

        $this->denyAccessUnlessGranted('create', $offers);


        $form = $this->createForm(OfferType::class, $offers);
        $form->handleRequest($request);

        $route = $request->get('_route');

        if ($form->isSubmitted() && $form->isValid())
        {
            $image = $form->get('file')->getData();
            if ($image != null){
                $offers->setFile($uploadService->uploadImage($image, UploadService::OFFERS_FOLDER_NAME));
            }

            $slug = $slugger->slug($offers->getTitle());
            $offers->setSlug($slug);

            if ($route === 'new_request') {
                $offers->setUsers($this->getUser());
            }

            $em->persist($offers);

            try {
                $em->flush();
                $this->addFlash('success', $translator->trans('success_msg'));
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('error_msg'));

                return $this->redirectToRoute($route);
            }

            $targetPath = $route === 'new_offer' ? 'show_offer' : 'show_request';
            return $this->redirectToRoute($targetPath, ['slug' => $slug]);
        }

        return $this->render('offer/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'route' => $route
        ]);
    }

    /**
     * @Route("/offres/modification/{slug}", name="edit_offer")
     * @Route("/demandes/modification/{slug}", name="edit_request")
     */
    public function edit(
        Request $request,
        Offers $offers,
        EntityManagerInterface $em,
        UploadService $uploadService,
        SluggerInterface $slugger,
        TranslatorInterface $translator
    ): Response
    {
        if ($offers->getIsDeleted()) {
            throw new HttpException('410');
        }

        $this->denyAccessUnlessGranted('edit', $offers);

        $offersOldPicture = $offers->getFile();

        $form = $this->createForm(OfferType::class, $offers);
        $form->handleRequest($request);
        $route = $request->get('_route');

        if ($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('file')->getData();
            if ($imageChange != null){
                $uploadService->deleteImage($offersOldPicture, UploadService::OFFERS_FOLDER_NAME);
                $offers->setFile($uploadService->uploadImage($imageChange, UploadService::OFFERS_FOLDER_NAME));
            }

            $oldSlug = $offers->getSlug();
            $slug = $slugger->slug($offers->getTitle());
            $offers->setSlug($slug);

            try {
                $em->flush();
                $this->addFlash('success', $translator->trans('success_msg'));
            } catch (Exception $e) {
                $this->addFlash('error', $translator->trans('error_msg'));

                return $this->redirectToRoute($route, ['slug' => $oldSlug]);
            }

            $targetPath = $route === 'edit_offer' ? 'show_offer' : 'show_request';

            return $this->redirectToRoute($targetPath, ['slug' => $offers->getSlug()]);
        }

        return $this->render('offer/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offers,
            'route' => $route
        ]);
    }

    /**
     * @Route("/offres/anonymisation/{slug}", name="anonymize_offer")
     * @Route("/demandes/anonymisation/{slug}", name="anonymize_request")
     */
    public function delete(
        Offers $offers,
        EntityManagerInterface $em,
        Request $request,
        AnonymizeService $anonymizeService,
        TranslatorInterface $translator
    ): Response
    {
        if ($offers->getIsDeleted()) {
            throw new HttpException('410');
        }
        $this->denyAccessUnlessGranted('anonymize', $offers);

        $route = $request->get('_route');
        $targetPath = $route === 'anonymize_offer' ? 'offers' : 'requests';
        $anonymizeService->anonymizeOffer($offers);

        $em->flush();
        $this->addFlash('success', $translator->trans('success_msg'));

        return $this->redirectToRoute($targetPath);
    }

    /**
     * @Route("/demandes/{slug}", name="show_request")
     * @Route("/offres/{slug}", name="show_offer")
     */
    public function show(Request $request,Offers $offers): Response
    {
        if ($offers->getIsDeleted()) {
            throw new HttpException('410');
        }

        $route = $request->get('_route');
        if ($route === 'show_request' && !$offers->isARequest()) {
            return $this->redirectToRoute('show_offer', ['slug' => $offers->getSlug()]);
        } elseif ($route === 'show_offer' && $offers->isARequest()) {
            return $this->redirectToRoute('show_request', ['slug' => $offers->getSlug()]);
        }

        return $this->render('offer/show.html.twig', [
            'offer' => $offers
        ]);
    }
}
