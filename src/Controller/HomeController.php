<?php

namespace App\Controller;

use App\Entity\RequestParam;
use App\Entity\WeatherApiRequest;
use App\Entity\WeatherApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
     * @return JsonResponse
     */
    public function getWeather(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //Api data fetching
        $openWeatherApi = new WeatherApiRequest(
            'https://api.openweathermap.org/data/2.5/weather?',
            'query',
            new RequestParam('q', $data['city']),
            new RequestParam('appid', $data['apiKey'])
        );

        $weatherInfo = $openWeatherApi->fetchWeatherApi();

        $croppedData = $openWeatherApi->cropResponseData($weatherInfo);

        return new JsonResponse(json_decode($croppedData->formatToJson($croppedData)), Response::HTTP_OK);
    }

}
