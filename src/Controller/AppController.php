<?php

namespace App\Controller;


use App\Service\CallApiService;
use App\Repository\CityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(Request $request): Response
    {
        // Gathering data from form
        $city = @htmlspecialchars($request->request->get('form-city'));
        $sector = @htmlspecialchars($request->request->get('form-sector'));

        // if form isn't submitted it returned the index
        if ($city == null || $sector == null) {
            return $this->render('app/index.html.twig');
        }
        // else it return to the results_path with data
        return $this->redirectToRoute('app_results', ['city' => $city, 'sector' => $sector, 'page' => 1]);
    }

    /**
     * @Route("/city={city}&sector={sector}&page={page}", name="app_results")
     * 
     */
    public function search(CallApiService $callApiService, CityRepository $CityRepository, int $page, RateLimiterFactory $anonymousApiLimiter, Request $request, string $city, string $sector): Response
    {
        // create a limiter based on a unique identifier of the client
        // (e.g. the client's IP address, a username/email, an API key, etc.)
        // https://symfony.com/doc/5.4/rate_limiter.html
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        // Number of remaining tokens before getting blocked by the limiter
        $limitRemainingTokens = $limiter->consume(0)->getRemainingTokens();

        // the argument of consume() is the number of tokens to consume
        // and returns an object of type Limit
        if (false === $limiter->consume(1)->isAccepted()) {
            $companies = [];
            $search = ['city' => '', 'sector' => '', 'page' => '', 'resultsNumber' => 0];
            $header = 'Trop de demandes, veuillez rééssayer plus tard';
        } else {
            $communeId = @$CityRepository->findCommuneIdByCityName($city)[0]['commune_id'];

            // Return an empty array if there is no results
            // Else it returns companies
            if (!isset($communeId) || !isset($sector)) {
                $companies = [];
                $search = ['city' => '', 'sector' => '', 'page' => '', 'resultsNumber' => 0];
                $header = 'Aucun résultat pour \'' . $sector . '\' dans la ville de \'' . $city . '\'';
            } else {
                // Setting up companies Array with the results of the Api Call
                $results = $callApiService->getCompanies($communeId, $sector, $page);
                $companies = $results["companies"];
                $header = $results['companies_count'] . ' résultat(s) pour \'' . $sector . '\' dans la ville de \'' . $city . '\' (nombres de token restant : ' . $limitRemainingTokens . ')';
                $search = ['city' => $city, 'sector' => $sector, 'page' => $page, 'resultsNumber' => $results['companies_count']];
            }
        }

        /**
         * companies = Array of results
         * header = Text of research
         * search = Array with commune_id, sector and page
         */
        return $this->render('app/results.html.twig', [
            'companies' => $companies,
            'header' => $header,
            'search' => $search
        ]);
    }
}
