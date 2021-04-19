<?php

namespace App\Controller;

use App\Form\StackoverflowProxyType;
use App\Service\StackoverflowApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

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
     * @OA\Response(
     *     response=200,
     *     description="Returns stackoverflow questions that match",
     * )
     * @OA\Parameter(
     *     name="tagged",
     *     in="query",
     *     description="Tags separated by semicolon",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="fromdate[day]",
     *     in="query",
     *     description="From date,part of date range(day)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="fromdate[month]",
     *     in="query",
     *     description="From date,part of date range(month)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="fromdate[year]",
     *     in="query",
     *     description="From date,part of date range(year)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="todate[day]",
     *     in="query",
     *     description="To date,part of date range(day)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="todate[month]",
     *     in="query",
     *     description="To date,part of date range(month)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="todate[year]",
     *     in="query",
     *     description="To date,part of date range(year)",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="Order by desc or asc",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="sort",
     *     in="query",
     *     description="Sort by activity, votes, creation, hot, week or month",
     *     @OA\Schema(type="string")
     * )
    */
    public function api(Request $request, StackoverflowApi $api): Response
    {
        $now=new DateTime();
        $last_week = new DateTime();
        $last_week->modify('-1 week');
        $defaultData = [
            'tagged' => 'php',
            'fromdate'=>$last_week,
            'todate'=> $now,
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
