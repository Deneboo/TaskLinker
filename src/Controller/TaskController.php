<?php

namespace App\Controller;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/project/{projectId}/task/new', name: 'app_task_new')]
    public function new(Request $request, EntityManagerInterface $manager, ProjectRepository $projectRepository, int $projectId): Response
    {
        $project = $projectRepository->find($projectId);
        $task = new Task();
        $task->setProject($project);
        if (!$project) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(TaskType::class, $task, [
            'users' => $project->getUsers()->toArray(),
        ]);
       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setStartedAt(new \DateTimeImmutable());
            if ($task->getStatus() === TaskStatus::DONE) {
                $task->setEndedAt(new \DateTimeImmutable());
            }
            $manager->persist($task);
            $manager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('task/new.html.twig', [
            'form' => $form,
            'project' => $project,
        ]);
    }

    #[Route('/project/{projectId}/task/edit{id}', name: 'app_task_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $manager, ProjectRepository $projectRepository, int $projectId, Task $task): Response
    {
        $project = $projectRepository->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(TaskType::class, $task, [
            'users' => $project->getUsers()->toArray(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($task->getStatus() === TaskStatus::DONE) {
                $task->setEndedAt(new \DateTimeImmutable());
            }

            $manager->flush();

            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form,
            'project' => $project,
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/remove', name: 'app_task_remove', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function remove(TaskRepository $taskRepository, EntityManagerInterface $manager, ?int $id): Response
    {
        $task = $taskRepository->find($id);
        $project = $task->getProject();
        if (!$task) {
            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
        }

        $manager->remove($task);
        $manager->flush();

        return $this->redirectToRoute('app_project_show', ['id' => $project->getId()], Response::HTTP_SEE_OTHER);
    }
}
