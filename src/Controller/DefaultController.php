<?php

namespace App\Controller;

use App\Repository\OffersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(OffersRepository $offersRepository): Response
    {
        $lastOffers = $offersRepository->getLastOffers('associations');
        $lastRequests = $offersRepository->getLastOffers('users');

        return $this->render('default/homepage.html.twig', [
            'lastOffers' => $lastOffers,
            'lastRequests' => $lastRequests,
        ]);
    }

    /**
     * @Route("/rgpd", name="rgpd")
     */
    public function rgpd(): Response
    {
        return $this->render('default/rgpd.html.twig');
    }
}
