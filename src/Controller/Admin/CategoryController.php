<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoryType;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    /**
     * @Route("/categories/creation", name="new_category")
     */
    public function create(Request $request, EntityManagerInterface $em, UploadService $uploadService, SluggerInterface $slugger): Response
    {

        $category = new Categories();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $image = $form->get('picture')->getData();
            if ($image != null){
                $category->setPicture($uploadService->uploadImage($image, 'categories'));
            }

            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('show_category', ['slug' => $slug]);
        }

        return $this->render('admin/category/create-and-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }


}
