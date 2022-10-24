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
        dd($callApiService->getApi('distance=30&latitude=49.119146&longitude=6.17602&rome_codes=M1607'));

        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
