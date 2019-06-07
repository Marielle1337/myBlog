<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleController extends AbstractController
{
    public function __construct(ArticleRepository $articleRepository, ObjectManager $manager)
    {
        $this->articleRepository = $articleRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/articles", name="article.list")
     */
    public function index() : Response
    {
        $articles = $this->articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route(
     * "/article/{slug}-{id}",
     * name="article.show",
     * requirements={"slug": "[a-z0-9\-]*"}
     * )
     */
    public function show(Article $article, string $slug) : Response
    {
        $articleSlug = $article->getSlug();
        if ($slug !== $articleSlug) {
            return $this->redirectToRoute('article.show', [
                'id' => $article->getId(),
                'slug' => $articleSlug
            ], 301);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }
}
