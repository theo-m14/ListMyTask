<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\TaskList;
use App\Repository\TaskRepository;
use App\Repository\TaskListRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/taskList/{id}/createTask', name: 'app_task_create')]
    public function create(TaskList $taskList,Request $request, TaskRepository $taskManager): Response
    {

        if(!$taskList){
            $this->addFlash('error', "L'id fourni ne correspond à aucune de liste existante");
            return $this->redirectToRoute('app_taskList_seeAll');
        }

        $newTask = new Task();

        $form = $this->createForm(TaskType::class, $newTask);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $newTask->setTaskList($taskList);

            $taskManager->add($newTask);

            return $this->redirectToRoute('app_taskList_seeOne', ['id' => $taskList->getId()]);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/editTask/{id}', name:"app_task_edit")]
    public function edit(Task $task,Request $request, TaskRepository $taskManager) : Response
    {
        if(!$task){
            $this->addFlash('error', "L'id fourni ne correspond à aucune de liste ou tâche existante");
            return $this->redirectToRoute('app_taskList_seeAll');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $taskManager->add($task);

            return $this->redirectToRoute('app_taskList_seeOne', ['id' => $task->getTaskList()->getId()]);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/taskList/{id}/deleteDoneTask', name:"app_task_delete")]
    public function delete(TaskList $taskList, TaskRepository $taskManager) : Response
    {
        if(!$taskList){
            $this->addFlash('error', "L'id fourni ne correspond à aucune de liste ou tâche existante");
            return $this->redirectToRoute('app_taskList_seeAll');
        }

        foreach($taskList->getTasks() as $task){
            if($task->isDone()){
                $taskManager->remove($task);
            }
        }
        
        return $this->redirectToRoute('app_taskList_seeOne', ['id' => $taskList->getId()]);
    }

    #[Route('/task/{id}/changeStatus' , name:"app_task_changeStatus")]
    public function changeStatus(Task $task, TaskRepository $taskManager)
    {
        if($task){
            $task->setDone(!$task->isDone());
            $taskManager->add($task);
            return new JsonResponse([]);
        }
    }
}
