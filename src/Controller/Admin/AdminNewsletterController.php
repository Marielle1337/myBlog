<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Newsletter;
use App\Repository\NewsletterRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\NewsletterType;
use Symfony\Component\HttpFoundation\Request;

class AdminNewsletterController extends AbstractController
{
    public function __construct(NewsletterRepository $newsletterRepository, ObjectManager $manager)
    {
        $this->newsletterRepository = $newsletterRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/newsletter", name="admin.newsletter.index")
     */
    public function index() : Response
    {
        $newsletters = $this->newsletterRepository->findAll();

        return $this->render('admin/newsletter/index.html.twig', compact('newsletters'));
    }

    /**
     * @Route("/admin/newsletter/new", name="admin.newsletter.new")
     */
    public function new(Request $request) : Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($newsletter);
            $this->manager->flush();
            $this->addFlash('success', 'Votre newsletter a bien été enregistré');

            return $this->redirectToRoute('admin.newsletter.index');
        }

        return $this->render('admin/newsletter/new.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/newsletter/{id}",
     * name="admin.newsletter.edit",
     * methods="GET|POST"
     * )
     */
    public function edit(Newsletter $newsletter, Request $request) : Response
    {
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'Votre newsletter a bien été modifié');

            return $this->redirectToRoute('admin.newsletter.index');
        }

        return $this->render('admin/newsletter/edit.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/newsletter/{id}",
     * name="admin.newsletter.delete",
     * methods="DELETE"
     * )
     */
    public function delete(Newsletter $newsletter, Request $request) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $newsletter->getId(), $request->get('_token'))) {
            $this->manager->remove($newsletter);
            $this->manager->flush();
            $this->addFlash('success', 'Votre newsletter a bien été supprimé');
        }
        return $this->redirectToRoute('admin.newsletter.index');
    }
}
