<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoryType;
use App\Service\UploadService;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    // todo : split this into 2 controllers (admin/public)

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

            try {
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash('warning', 'not_unique_name');

                return $this->redirectToRoute('new_category');
            }

            return $this->redirectToRoute('show_category', ['slug' => $slug]);
        }

        return $this->render('admin/category/create-and-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
      * @Route("/categories", name="category")
      */
     public function list(Request $request, CategoriesRepository $repository): Response
     {
        $route = $request->get('_route');

        $page = $request->query->getInt('page', 1);
        if ($page <= 0) {
            $page = 1;
            $paginator = $repository->findAllCategories($page, 3);
        } else {
            $paginator = $repository->findAllCategories($page, 3);
            $paginator = empty($paginator['items']) ? $repository->findAllCategories(1, 3) : $paginator;
        }
        return $this->render('admin/category/list.html.twig', [
            'paginator' => $paginator,
            'route' =>$route
        ]);
     }

    /**
     * @Route("/categories/modification/{slug}", name="edit_category")
     */
    public function edit(Request $request, Categories $category, EntityManagerInterface $em, SluggerInterface $slugger, UploadService $uploadService): Response
    {
        if ($category->getIsDeleted()) {
            throw new HttpException('410');
        }

        $categoryOldPicture = $category->getPicture();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $imageChange = $form->get('picture')->getData();
            if($imageChange != null){
                $uploadService->deleteImage($categoryOldPicture, 'categories');
                $category->setPicture($uploadService->uploadImage($imageChange, 'categories'));
            }

            $oldSlug = $category->getSlug();
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            try {
                $em->flush();
            } catch (Exception $e) {
                $this->addFlash('warning', 'not_unique_name');

                return $this->redirectToRoute('edit_category', ['slug' => $oldSlug]);
            }

            return $this->redirectToRoute('show_category', ['slug' => $category->getSlug()]);
        }

        return $this->render('admin/category/create-and-edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/categories/suppression/{slug}", name="delete_category")
     */
    public function delete(Categories $category, EntityManagerInterface $em): Response
    {
        if ($category->getIsDeleted()) {
            throw new HttpException('410');
        }

        $actualParentOfTheCategory = $category->getParent();

        $category->setIsDeleted(1);

        $category->setName('deleted');
        $category->setDescription('deleted');
        $category->setPicture('deleted');
        $category->setColor('#000000');
        $category->setParent(null);

        foreach ($category->getChildren() as $item){ // When we delete a parent with childrens => childrens get parent category of parent or null (if children's parent had no parent)
            $item->setParent($actualParentOfTheCategory);
        }

        try {
            $em->flush();
        } catch (Exception $e) {
            $this->addFlash('warning', 'an_error_occurred');

            return $this->redirectToRoute('category');
        }

        return $this->redirectToRoute('category'); // In futur this should redirect user to homepage
    }

    /**
     * @Route("/categories/{slug}", name="show_category")
     */
    public function show(Categories $category): Response
    {
        if ($category->getIsDeleted()) {
            throw new HttpException('410');
        }

        $childrens = $category->getChildren();

        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
            'childrens' => $childrens
        ]);
    }

}
