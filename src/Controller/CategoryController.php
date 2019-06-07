<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryController extends AbstractController
{
    public function __construct(CategoryRepository $categoryRepository, ObjectManager $manager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/categories", name="category.list")
     */
    public function index() : Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route(
     * "/category/{slug}-{id}",
     * name="category.show",
     * requirements={"slug": "[a-z0-9\-]*"}
     * )
     */
    public function show(Category $category, string $slug) : Response
    {
        $categorySlug = $category->getSlug();
        if ($slug !== $categorySlug) {
            return $this->redirectToRoute('category.show', [
                'id' => $category->getId(),
                'slug' => $categorySlug
            ], 301);
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'articles' => $category->getArticles(),
            'challenges' => $category->getChallenges()
        ]);
    }
}
