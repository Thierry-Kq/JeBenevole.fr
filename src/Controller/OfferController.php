<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Form\OfferType;
use App\Repository\OffersRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     */
    public function create(Request $request, EntityManagerInterface $em, UploadService $uploadService, SluggerInterface $slugger): Response
    {
        // todo : requests

        $offers = new Offers();

        $form = $this->createForm(OfferType::class, $offers);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
//            $image = $form->get('picture')->getData();
//            if ($image != null){
//                $association->setPicture($uploadService->uploadImage($image, 'associations'));
//            }

            $slug = $slugger->slug($offers->getTitle());
            $offers->setSlug($slug);

            $offers->setDateEnd(new \DateTime());
            $offers->setDateStart(new \DateTime());
            $em->persist($offers);
            $em->flush();
            return $this->redirectToRoute('show_offer', ['slug' => $slug]);
        }

        return $this->render('offer/create-and-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


// todo : requests + gestion image + gestion user/asso
    /**
     * @Route("/offres/modification/{slug}", name="edit_offer")
     */
    public function edit(Request $request, Offers $offers, EntityManagerInterface $em, SluggerInterface $slugger, UploadService $uploadService): Response
    {
//        $associationOldPicture = $association->getPicture();

        $form = $this->createForm(OfferType::class, $offers);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
//            $imageChange = $form->get('picture')->getData();
//            if($imageChange != null){
//                $uploadService->deleteImage($associationOldPicture, 'associations');
//                $association->setPicture($uploadService->uploadImage($imageChange, 'associations'));
//            }else{
//                $association->setPicture($associationOldPicture);
//            }

            $offers = $form->getData();
            $slug = $slugger->slug($offers->getTitle());
            $offers->setSlug($slug);
            $em->flush();

            return $this->redirectToRoute('show_offer', ['slug' => $offers->getSlug()]);
        }

        return $this->render('offer/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offers
        ]);
    }
//
    /**
     * @Route("/offres/suppression/{slug}", name="delete_offre")
     * @Route("/demandes/suppression/{slug}", name="delete_request")
     */
    public function delete(Offers $offers,
        EntityManagerInterface $em,
        Request $request
    ): Response
    {
        $route = $request->get('_route');

        $targetPath = $route === 'delete_offre' ? 'offers' : 'requests';

        $offers->setIsDeleted(1);

        // todo : finish this
        $offers->setTitle('deleted');
        $offers->setAddress('deleted');
        $offers->setZip('00000');
        $offers->setCity('deleted');
        $offers->setDescription('deleted');

        $em->flush();

        return $this->redirectToRoute($targetPath);
    }
// todo : separe this route for requests
    /**
     * @Route("/offres/{slug}", name="show_offer")
     */
    public function show(Offers $offers): Response
    {

        return $this->render('offer/show.html.twig', [
            'offer' => $offers
        ]);
    }
}
