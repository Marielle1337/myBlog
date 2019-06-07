<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $articles = $this->articleRepository->findBy([], [
            'date' => 'DESC'
        ]);

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
