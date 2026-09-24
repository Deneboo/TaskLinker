<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
