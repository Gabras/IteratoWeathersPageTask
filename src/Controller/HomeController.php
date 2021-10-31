<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/weather", name="weather_getter")
     * @param Request $request
     */
    public function getWeather(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //Logic for 3rd party API

        return new JsonResponse($data['city'], Response::HTTP_OK);
    }
}
