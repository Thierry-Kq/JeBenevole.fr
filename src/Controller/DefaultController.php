<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {
        return $this->render('default/homepage.html.twig');
    }

    /**
     * @Route("/rgpd", name="rgpd")
     */
    public function rgpd(): Response
    {
        return $this->render('default/rgpd.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('default/contact.html.twig');
    }

    /**
     * @Route("/equipe", name="team")
     */
    public function team(): Response
    {
        return $this->render('default/team.html.twig');
    }
}
