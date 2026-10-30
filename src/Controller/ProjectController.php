<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Enum\ProjectStatus;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Security\Voter\ProjectVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $projects = $projectRepository->findByStatus(ProjectStatus::IN_PROGESS);
        } else {
            $projects = $projectRepository->findByUserAndStatus($user, ProjectStatus::IN_PROGESS);
        }

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[IsGranted(ProjectVoter::VIEW, subject: 'project')]
    #[Route('/project/{id}', name: 'app_project_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Project $project): Response
    {
        if (ProjectStatus::ARCHIVED == $project->getStatus()) {
            $this->addFlash('error', 'Ce projet n\'existe pas.');

            return $this->redirectToRoute('app_home');
        }
        $users = $project?->getUsers();
        $tasks = $project?->getTasks();

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'users' => $users,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/project/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {
        $project = new Project();
        $users = $userRepository->findAll();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setStartedAt(new \DateTimeImmutable());
            $project->setStatus(ProjectStatus::IN_PROGESS);
            $manager->persist($project);
            $manager->flush();
            $this->addFlash('success', 'Le projet "'.$project->getTitle().'" a été créé avec succès.');

            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/new.html.twig', [
            'users' => $users,
            'form' => $form,
        ]);
    }

    #[Route('/project/{id}/edit', name: 'app_project_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, EntityManagerInterface $manager, UserRepository $userRepository, TaskRepository $taskRepository, Project $project): Response
    {
        $oldUsers = $project->getUsers()->toArray();
        $users = $userRepository->findAll();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUsers = $project->getUsers()->toArray();

            $removedUsers = array_udiff(
                $oldUsers,
                $newUsers,
                fn (User $a, User $b) => $a->getId() <=> $b->getId()
            );
            $tasks = $taskRepository->getTaskByProject($project->getId());

            foreach ($tasks as $task) {
                foreach ($removedUsers as $removedUser) {
                    if ($task->getUsers()->contains($removedUser)) {
                        $task->removeUser($removedUser);
                    }
                }
            }

            $manager->persist($project);
            $manager->flush();
            $this->addFlash('success', 'Le projet "'.$project->getTitle().'" a été modifié avec succès.');

            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'users' => $users,
            'form' => $form,
        ]);
    }

    #[Route('/project/{id}/remove', name: 'app_project_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function remove(Project $project, EntityManagerInterface $manager): Response
    {
        $project->setStatus(ProjectStatus::ARCHIVED);
        $manager->flush();
        $this->addFlash('success', 'Le projet "'.$project->getTitle().'" a été supprimé.');

        return $this->redirectToRoute('app_home');
    }
}
