<?php

namespace App\Controller\Admin;

use App\Entity\Associations;
use App\Repository\AssociationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/administration/users", name="admin_users")
     */
    public function dashboard(){
        // todo : do something

        return $this->render('admin/dashboard.html.twig');
    }
}
