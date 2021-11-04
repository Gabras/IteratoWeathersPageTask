<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController'
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

        $weatherInfo = $this->fetchWeatherApi($data);

        //End of basic logic

        return new JsonResponse($weatherInfo, Response::HTTP_OK);
    }

    public function fetchWeatherApi($data)
    {
        try {
            $response = HttpClient::create()->request('GET', 'https://api.openweathermap.org/data/2.5/weather?', [
                'query' => [
                    'q' => $data['city'],
                    'appid' => $data['apiKey'],
                ],
            ]);
            return json_decode($response->getContent());
        } catch (\HttpException $exception) {
            return $exception;
        }
    }
}
