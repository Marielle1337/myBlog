<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;

class UserController extends AbstractController
{
    public function __construct(UserRepository $userRepository, ObjectManager $manager)
    {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/users", name="user.list")
     */
    public function index() : Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route(
     * "/user/{slug}-{id}",
     * name="user.show",
     * requirements={"slug": "[a-z0-9\-]*"}
     * )
     */
    public function show(User $user, string $slug) : Response
    {
        $userSlug = $user->getSlug();
        if ($slug !== $userSlug) {
            return $this->redirectToRoute('user.show', [
                'id' => $user->getId(),
                'slug' => $userSlug
            ], 301);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }
}
