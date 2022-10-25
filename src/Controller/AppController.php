<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CityRepository;

class AppController extends AbstractController
{

    /**
     * @Route("/", name="app")
     */
    public function index(CallApiService $callApiService, CityRepository $CityRepository): Response
    {
        $communeId = $CityRepository->findCommuneIdByCityName('Nantes');

        $sector = 'boucher';

        // Return an empty array if there is no results
        // Else it returns companies
        if (!isset($communeId[0]['commune_id']) || !isset($sector)) {
            $companies = [];
        } else {
            // Setting up companies Array with the results of the Api Call
            $results = $callApiService->getCompanies($communeId[0]['commune_id'], $sector);
            $companies = $results["companies"];
        }

        return $this->render('app/index.html.twig', [
            'companies' => $companies
        ]);
    }
}
