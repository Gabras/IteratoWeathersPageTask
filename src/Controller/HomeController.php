<?php

namespace App\Controller;

use App\Model\RequestParam;
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

        //Api data fetching
        $weatherInfo = $this->fetchWeatherApi($data);

        //$testArray = ['Birstonas', 'Vilnius', 'Kaunas'];
        return new JsonResponse($weatherInfo, Response::HTTP_OK);
    }

    public function fetchWeatherApi($data)
    {
        $param1 = new RequestParam('q', $data['city']);
        $param2 = new RequestParam('appid', $data['apiKey']);
        $url = 'https://api.openweathermap.org/data/2.5/weather?';
        try {
            $response = HttpClient::create()->request('GET', $url, [
                'query' => [
                    $param1->getKey() => $param1->getValue(),
                    $param2->getKey() => $param2->getValue(),
                ],
            ]);
            return json_decode($response->getContent());
        } catch (\HttpException $exception) {
            return $exception;
        }
    }
}
