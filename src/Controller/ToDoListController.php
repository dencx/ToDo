<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([],['id' => 'DESC']);
        return $this->render('index.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/create", name="create_task")
     */
    public function create(Request $request): Response
    {
        $title = trim($request->request->get('title'));
        if(empty($title))
        return $this->redirectToRoute('to_do_list');
        
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task;
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');

    
    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setStatus(! $task->getStatus());
        $entityManager->flush();
        return $this->redirectToRoute('to_do_list');
    }


    /**
     * @Route("/delete/{id}", name="task_delete")
     */
    public function delete(Task  $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($id);
        $entityManager->flush();
        return $this->redirectToRoute('to_do_list');
    }

}
