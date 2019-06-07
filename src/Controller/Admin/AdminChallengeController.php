<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Challenge;
use App\Repository\ChallengeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\ChallengeType;
use Symfony\Component\HttpFoundation\Request;

class AdminChallengeController extends AbstractController
{
    public function __construct(ChallengeRepository $challengeRepository, ObjectManager $manager)
    {
        $this->challengeRepository = $challengeRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/challenge", name="admin.challenge.index")
     */
    public function index() : Response
    {
        $challenges = $this->challengeRepository->findBy([], [
            'lastUpdate' => 'DESC'
        ]);

        return $this->render('admin/challenge/index.html.twig', compact('challenges'));
    }

    /**
     * @Route("/admin/challenge/new", name="admin.challenge.new")
     */
    public function new(Request $request) : Response
    {
        $challenge = new Challenge();
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($challenge);
            $this->manager->flush();
            $this->addFlash('success', 'Votre challenge a bien été enregistré');

            return $this->redirectToRoute('admin.challenge.index');
        }

        return $this->render('admin/challenge/new.html.twig', [
            'challenge' => $challenge,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/challenge/{id}",
     * name="admin.challenge.edit",
     * methods="GET|POST"
     * )
     */
    public function edit(Challenge $challenge, Request $request) : Response
    {
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'Votre challenge a bien été modifié');

            return $this->redirectToRoute('admin.challenge.index');
        }

        return $this->render('admin/challenge/edit.html.twig', [
            'challenge' => $challenge,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/challenge/{id}",
     * name="admin.challenge.delete",
     * methods="DELETE"
     * )
     */
    public function delete(Challenge $challenge, Request $request) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $challenge->getId(), $request->get('_token'))) {
            $this->manager->remove($challenge);
            $this->manager->flush();
            $this->addFlash('success', 'Votre challenge a bien été supprimé');
        }
        return $this->redirectToRoute('admin.challenge.index');
    }
}
