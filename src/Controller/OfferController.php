<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Form\OfferType;
use App\Repository\OffersRepository;
use App\Service\UploadService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        SluggerInterface $slugger
    ): Response
    {
        // todo : if user without asso on offer  -> redirect to request ( set a ROLE for associations owner ? )
        // todo : so maybe split this route or this Controller

        $offers = new Offers();

        $form = $this->createForm(OfferType::class, $offers);
        $form->handleRequest($request);

        $route = $request->get('_route');

        if ($form->isSubmitted() && $form->isValid())
        {
            $image = $form->get('file')->getData();
            if ($image != null){
                $offers->setFile($uploadService->uploadImage($image, 'offers'));
            }

            $slug = $slugger->slug($offers->getTitle());
            $offers->setSlug($slug);

//            $offers->setUsers($users);
//            $offers->setAssociations();
            $em->persist($offers);
            try {
                $em->flush();
            } catch (Exception $e){
                $this->addFlash('warning', 'non_unique');
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
        SluggerInterface $slugger,
        UploadService $uploadService
    ): Response
    {
        if ($offers->getIsDeleted()) {
            throw new HttpException('410');
        }

        $offersOldPicture = $offers->getFile();

        $form = $this->createForm(OfferType::class, $offers);
        $form->handleRequest($request);
        $route = $request->get('_route');

        if ($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('file')->getData();
            if ($imageChange != null){
                $uploadService->deleteImage($offersOldPicture, 'offers');
                $offers->setFile($uploadService->uploadImage($imageChange, 'offers'));
            } else {
                $offers->setFile($offersOldPicture);
            }

            $oldSlug = $offers->getSlug();
            $offers = $form->getData();
            $newSlug = $slugger->slug($offers->getTitle());
            $offers->setSlug($newSlug);

            try {
                $em->flush();
            } catch (Exception $e){
                $this->addFlash('warning', 'non_unique');
                return $this->redirectToRoute($route, ['slug' => $oldSlug]);
            }

            $targetPath = $route === 'edit_offer' ? 'show_offer' : 'show_request';

            return $this->redirectToRoute($targetPath, ['slug' => $newSlug]);
        }

        return $this->render('offer/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offers,
            'route' => $route
        ]);
    }
//
    /**
     * @Route("/offres/suppression/{slug}", name="delete_offer")
     * @Route("/demandes/suppression/{slug}", name="delete_request")
     */
    public function delete(
        Offers $offers,
        EntityManagerInterface $em,
        Request $request,
        UploadService $uploadService
    ): Response
    {
        if ($offers->getIsDeleted()) {
            throw new HttpException('410');
        }

        $route = $request->get('_route');

        $targetPath = $route === 'delete_offer' ? 'offers' : 'requests';

        $offers->setIsDeleted(1);

        $offers->setTitle('deleted');
        $offers->setAddress('deleted');
        $offers->setZip('00000');
        $offers->setCity('deleted');
        $offers->setDescription('deleted');
        $offers->setContactExternalName('deleted');
        $offers->setContactExternalEmail('deleted');
        $offers->setContactExternalTel('deleted');
        $uploadService->deleteImage($offers->getFile(), 'offers');

        $em->flush();

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
