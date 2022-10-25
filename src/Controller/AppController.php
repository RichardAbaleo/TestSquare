<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{

    /**
     * @Route("/", name="app")
     */
    public function index(CallApiService $callApiService): Response
    {
        $results = $callApiService->getApi('commune_id=54431&departments=54&rome_codes=M1607');

        $companies = $results["companies"];

        //dd($companies);

        return $this->render('app/index.html.twig', [
            'results' => $results,
            'companies' => $companies
        ]);
    }
}
