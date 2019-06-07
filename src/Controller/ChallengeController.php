<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Challenge;
use App\Repository\ChallengeRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ChallengeController extends AbstractController
{
    public function __construct(ChallengeRepository $challengeRepository, ObjectManager $manager)
    {
        $this->challengeRepository = $challengeRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/challenges", name="challenge.list")
     */
    public function index() : Response
    {
        $challenges = $this->challengeRepository->findBy([], [
            'lastUpdate' => 'DESC'
        ]);

        return $this->render('challenge/index.html.twig', [
            'challenges' => $challenges
        ]);
    }

    /**
     * @Route(
     * "/challenge/{slug}-{id}",
     * name="challenge.show",
     * requirements={"slug": "[a-z0-9\-]*"}
     * )
     */
    public function show(Challenge $challenge, string $slug) : Response
    {
        $challengeSlug = $challenge->getSlug();
        if ($slug !== $challengeSlug) {
            return $this->redirectToRoute('challenge.show', [
                'id' => $challenge->getId(),
                'slug' => $challengeSlug
            ], 301);
        }

        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge
        ]);
    }
}
