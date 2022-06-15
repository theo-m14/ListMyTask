<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Form\TaskListType;
use App\Repository\TaskListRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskListController extends AbstractController
{

    #[Route('/', name: 'app_taskList_seeAll')]
    public function seeAll(TaskListRepository $taskListManager){
        $taskLists = $taskListManager->findAll();
        return $this->render('task_list/seeAll.html.twig', ['taskLists' => $taskLists]);
    }

    #[Route('/task-list/create', name: 'app_taskList_create')]
    public function create(Request $request, TaskListRepository $taskListManager): Response
    {

        $newTaskList = new TaskList();

        $form = $this->createForm(TaskListType::class, $newTaskList);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newTaskList->setDone(false);

            $taskListManager->add($newTaskList);

            return $this->redirectToRoute('app_taskList_seeOne', ['id' => $newTaskList->getId()]);
        }

        return $this->render('task_list/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/task-list/seeOne/{id}', name:'app_taskList_seeOne')]
    public function seeOne(TaskList $taskList,TaskListRepository $em) : Response
    {


        if($taskList){
            return $this->render('task_list/seeOne.html.twig', ['taskList' => $taskList]);
        }

        $this->addFlash('error', "L'id fourni ne correspond à aucune de liste existante");
        return $this->redirectToRoute('app_taskList_seeAll');
    }

    #[Route('/task-list/edit/{id}', name: 'app_taskList_edit')]
    public function edit(Request $request, TaskListRepository $taskListManager, TaskList $taskList): Response
    {

        if(!$taskList){
            $this->addFlash('error', "L'id fourni ne correspond à aucune de liste existante");
            return $this->redirectToRoute('app_taskList_seeAll');
        }

        $form = $this->createForm(TaskListType::class, $taskList);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

           $taskListManager->add($taskList);

            return $this->redirectToRoute('app_taskList_seeOne', ['id' => $taskList->getId()]);
        }

        return $this->render('task_list/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/task-list/delete/{id}', name:'app_taskList_delete')]
    public function delete(TaskList $taskList, TaskListRepository $taskListManager) : Response
    {
        if(!$taskList){
            $this->addFlash('error', "L'id fourni ne correspond à aucune de liste existante");
            return $this->redirectToRoute('app_taskList_seeAll');
        }

        $taskListManager->remove($taskList);

        return $this->redirectToRoute('app_taskList_seeAll');

    }
}
