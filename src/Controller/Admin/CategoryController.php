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

    // /**
    //  * @Route("/category", name="category")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('admin/category/index.html.twig', [
    //         'controller_name' => 'CategoryController',
    //     ]);
    // }

    /**
     * @Route("/categories/modification/{slug}", name="edit_category")
     */
    public function edit(Request $request, Categories $category, EntityManagerInterface $em, SluggerInterface $slugger, UploadService $uploadService): Response
    {
        $categoryOldPicture = $category->getPicture();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('picture')->getData();
            if($imageChange != null){
                $uploadService->deleteImage($categoryOldPicture, 'categories');
                $category->setPicture($uploadService->uploadImage($imageChange, 'categories'));
            }else{
                $category->setPicture($categoryOldPicture);
            }

            $category = $form->getData();
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);
            $em->flush();

            return $this->redirectToRoute('show_category', ['slug' => $category->getSlug()]);
        }

        return $this->render('admin/category/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

}
