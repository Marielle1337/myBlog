<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

class AdminCategoryController extends AbstractController
{
    public function __construct(CategoryRepository $categoryRepository, ObjectManager $manager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/category", name="admin.category.index")
     */
    public function index() : Response
    {
        $categories = $this->categoryRepository->findBy([], [
            'title' => 'ASC'
        ]);

        return $this->render('admin/category/index.html.twig', compact('categories'));
    }

    /**
     * @Route("/admin/category/new", name="admin.category.new")
     */
    public function new(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($category);
            $this->manager->flush();
            $this->addFlash('success', 'Votre catégorie a bien été enregistrée');

            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/category/{id}",
     * name="admin.category.edit",
     * methods="GET|POST"
     * )
     */
    public function edit(Category $category, Request $request) : Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'Votre catégorie a bien été modifiée');

            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/category/{id}",
     * name="admin.category.delete",
     * methods="DELETE"
     * )
     */
    public function delete(Category $category, Request $request) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->get('_token'))) {
            $this->manager->remove($category);
            $this->manager->flush();
            $this->addFlash('success', 'Votre catégorie a bien été supprimée');
        }
        return $this->redirectToRoute('admin.category.index');
    }
}
