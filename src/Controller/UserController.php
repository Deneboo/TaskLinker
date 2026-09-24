<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/équipe', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $user,
        ]);
    }

    #[Route('/employe/{id}', name: 'app_user_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('user/form.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/employe/{id}/remove', name: 'app_user_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(UserRepository $userRepository, EntityManagerInterface $manager, int $id): Response
    {
        $user = $userRepository->find($id);
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('app_user');
    }
}
