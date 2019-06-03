<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'name' => 'la page d\'acceuil',
        ]);
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function showArticles()
    {
        return $this->render('blog/index.html.twig', [
            'name' => 'les articles',
        ]);
    }
}
