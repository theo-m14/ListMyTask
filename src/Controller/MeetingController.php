<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Meeting;
use App\Form\MeetingType;
use App\Repository\MeetingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerR6jDNe8\PaginatorInterface_82dac15;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MeetingController extends AbstractController
{
    #[Route('/rendez-vous', name: 'app_meeting_showAll')]
    public function showAll(Request $request,MeetingRepository $meetingManager, PaginatorInterface $paginator): Response
    {
        $getAllMeetings = $meetingManager->findAll();

        $meetings = $paginator->paginate(
            $getAllMeetings, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('meeting/showAll.html.twig', [
            'meetings' => $meetings, 'paginator' => true
        ]);
    }

    #[Route('/rendez-vous/ajout', name: 'app_meeting_add')]
    public function add(Request $request,MeetingRepository $meetingManager)
    {
        $newMeeting = new Meeting();

        $form = $this->createForm(MeetingType::class, $newMeeting);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){

            $meetingManager->add($newMeeting, true);
            return $this->redirectToRoute('app_meeting_showAll');
        }

        return $this->render('meeting/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rendez-vous/edit/{id}', name: 'app_meeting_edit')]
    public function edit(Meeting $meeting,Request $request,MeetingRepository $meetingManager)
    {

        $form = $this->createForm(MeetingType::class, $meeting);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){

            $meetingManager->add($meeting, true);
            return $this->redirectToRoute('app_meeting_showAll');
        }

        return $this->render('meeting/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/rendez-vous/delete/{id}', name: 'app_meeting_delete')]
    public function delete(Meeting $meeting,MeetingRepository $meetingManager)
    {
        if(!$meeting){
            $this->addFlash('error', "L'id fourni ne correspond à aucun rendez-vous existant");
            return $this->redirectToRoute('app_meeting_showAll');
        }

        $meetingManager->remove($meeting, true);
        return $this->redirectToRoute('app_meeting_showAll');

    }

    #[Route('/rendez-vous/recherche', name:'app_meeting_search', methods:['POST'])]
    public function search(Request $request, MeetingRepository $meetingManager, PaginatorInterface $paginator){
        $acceptedFilter = ['meetingName', 'meetingPlace', 'meetingStartDate', 'meetingEndDate', 'meetingPriority'];
        
        $validSearchRequest = false;

        $searchParameters = [];

        foreach($request->request->all() as $key => $params){
            if(in_array($key,$acceptedFilter)){
                $searchParameters[$key] = $params; 
                if($params !== ""){
                    $validSearchRequest = true;
                }
            }
        }

        if(!$validSearchRequest){
            $getAllMeetings = $meetingManager->findAll();

        return new JsonResponse(['content' => $this->renderView('meeting/meetingContent.html.twig', ['meetings' => $getAllMeetings, 'paginator' => false])]);
        }

        $result = $meetingManager->searchMeetingByFilter($searchParameters);

        return new JsonResponse(['content' => $this->renderView('meeting/meetingContent.html.twig', ['meetings' => $result, 'paginator' => false])]);

    }
}
