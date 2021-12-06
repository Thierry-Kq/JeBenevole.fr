<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/administration", name="admin_dashboard")
     */
    public function dashboard(){
        // todo : do something

        return $this->render('admin/dashboard.html.twig');
    }
}
