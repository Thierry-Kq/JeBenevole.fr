<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/administration/offers", name="admin_offers")
     */
    public function offers(){
        // todo : do something

        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/administration/requests", name="admin_requests")
     */
    public function requests(){
        // todo : do something

        return $this->render('admin/dashboard.html.twig');
    }
}
