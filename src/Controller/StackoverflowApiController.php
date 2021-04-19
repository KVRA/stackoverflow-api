<?php

namespace App\Controller;

use App\Form\StackoverflowProxyType;
use App\Service\StackoverflowApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StackoverflowApiController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() {
        return $this->redirectToRoute('api');
    }
    /**
     * @Route("/api/query", name="api",methods={"GET"})
     * @param Request $request
     * @param StackoverflowApi $api
     * @return Response
    */
    public function api(Request $request, StackoverflowApi $api): Response
    {
        $now=new DateTime();
        $defaultData = [
            'tagged' => 'symfony',
            'todate'=> $now,
            'fromdate'=> $now
        ];
        $form = $this->createForm(StackoverflowProxyType::class,$defaultData);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            if ($form->isValid()) {
                // data is an array with "tagged", and maybe "todate", and "fromdate" keys
                $data=$form->getData();
                $from_date = $data['fromdate'] instanceof DateTime ? $data['fromdate']->setTime(2,0) : null ;
                $to_date = $data['todate'] instanceof DateTime ? $data['todate']->setTime(2,0) : null ;

                $response = $api->get($data['tagged'],$from_date,$to_date,$data['sort'],$data['order']);
                return $this->json($response['data'], $response['status']);
            } else if (in_array('application/json', $request->getAcceptableContentTypes(), true)){
                return $this->json($form->getErrors(true),400);
            }
        }

        return $this->render('stackoverflow_api/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
