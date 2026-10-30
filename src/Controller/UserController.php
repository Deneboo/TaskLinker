<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserRole;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    #[Route('/équipe', name: 'app_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $user,
        ]);
    }

    #[Route('/employe/{id}', name: 'app_user_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $roles = $form->get('roles')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            // Set employee role by default and add manager role if selected
            $user->setRoles([
                UserRole::Employee->value,
                ...(in_array(UserRole::Manager->value, $roles, true)
                    ? [UserRole::Manager->value]
                    : []),
            ]);
            $manager->flush();
            $this->addFlash('success', 'Le profil de "'.$user->getFirstName().' '.$user->getLastName().' " a été modifié avec succès.');

            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/form.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/employe/{id}/remove', name: 'app_user_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(UserRepository $userRepository, EntityManagerInterface $manager, int $id): Response
    {
        $user = $userRepository->find($id);
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Le profil de "'.$user->getFirstName().' '.$user->getLastName().' " a bien été modifié supprimé.');

        return $this->redirectToRoute('app_user');
    }
}
