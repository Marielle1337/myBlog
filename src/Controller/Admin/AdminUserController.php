<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

class AdminUserController extends AbstractController
{
    public function __construct(UserRepository $userRepository, ObjectManager $manager)
    {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/user", name="admin.user.index")
     */
    public function index() : Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/user/index.html.twig', compact('users'));
    }

    /**
     * @Route("/admin/user/new", name="admin.user.new")
     */
    public function new(Request $request) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();
            $this->addFlash('success', 'Votre user a bien été enregistré');

            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/user/{id}",
     * name="admin.user.edit",
     * methods="GET|POST"
     * )
     */
    public function edit(User $user, Request $request) : Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'Votre user a bien été modifié');

            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     * "/admin/user/{id}",
     * name="admin.user.delete",
     * methods="DELETE"
     * )
     */
    public function delete(User $user, Request $request) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $this->manager->remove($user);
            $this->manager->flush();
            $this->addFlash('success', 'Votre user a bien été supprimé');
        }
        return $this->redirectToRoute('admin.user.index');
    }
}
