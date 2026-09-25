<?php

namespace App\Controller;

use App\Entity\Project;
use App\Enum\ProjectStatus;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findByStatus(ProjectStatus::ACTIVE);
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/project/{id}', name: 'app_project_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Project $project): Response
    {
        $users = $project?->getUsers();
        $tasks = $project?->getTasks();

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'users' => $users,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/project/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, UserRepository $userRepository): Response
    {
        $project = new Project();
        $users = $userRepository->findAll();
        $form = $this->createForm(ProjectType::class, $project);
        
        $form->handleRequest($request);
        
         if ($form->isSubmitted() && $form->isValid()) {
            $project->setStartedAt(new \DateTimeImmutable());
            $project->setStatus(ProjectStatus::ACTIVE);
            $manager->persist($project);
            $manager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('project/new.index.html.twig', [
            'users' => $users,
            'form' => $form,
        ]);
    }

    #[Route('/project/{id}/edit', name: 'app_project_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $manager, UserRepository $userRepository, Project $project): Response
    {
        $users = $userRepository->findAll();
        $form = $this->createForm(ProjectType::class, $project);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($project);
            $manager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'users' => $users,
            'form' => $form,
        ]);
    }

    #[Route('/project/{id}/remove', name: 'app_project_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function remove(Project $project, EntityManagerInterface $manager): Response
    {
        $project->setStatus(ProjectStatus::ARCHIVED);
        $manager->flush();
        return $this->redirectToRoute('app_home');
    }
}
