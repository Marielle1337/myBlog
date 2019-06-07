<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    public function __construct(ArticleRepository $articleRepository, ObjectManager $manager)
    {
        $this->articleRepository = $articleRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin", name="admin.index")
     */
    public function index() : Response
    {
        return $this->render('admin/index.html.twig');
    }

}