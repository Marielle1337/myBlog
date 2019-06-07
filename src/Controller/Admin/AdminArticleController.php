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

class AdminArticleController extends AbstractController
{
    public function __construct(ArticleRepository $articleRepository, ObjectManager $manager)
    {
        $this->articleRepository = $articleRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/article", name="admin.article.index")
     */
    public function index() : Response
    {
        $articles = $this->articleRepository->findBy([], [
            'date' => 'DESC'
        ]);

        return $this->render('admin/article/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/admin/article/new", name="admin.article.new")
     */
    public function new(Request $request) : Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($article);
            $this->manager->flush();
            $this->addFlash('success', 'Votre article a bien été enregistré');

            return $this->redirectToRoute('article.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/article/{id}",
     * name="admin.article.edit",
     * methods="GET|POST"
     * )
     */
    public function edit(Article $article, Request $request) : Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'Votre article a bien été modifié');

            return $this->redirectToRoute('article.show', [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/article/{id}",
     * name="admin.article.delete",
     * methods="DELETE"
     * )
     */
    public function delete(Article $article, Request $request) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('_token'))) {
            $this->manager->remove($article);
            $this->manager->flush();
            $this->addFlash('success', 'Votre article a bien été supprimé');
        }
        return $this->redirectToRoute('admin.article.index');
    }
}
