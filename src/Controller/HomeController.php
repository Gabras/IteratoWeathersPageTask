<?php

namespace App\Controller;

use App\Entity\RequestParam;
use App\Entity\WeatherApiRequest;
use App\Entity\WeatherApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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
     * @Route("/api/weather", name="weather_getter", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @OA\Tag(name="Weather api")
     * @OA\Response(
     *     response=200,
     *     description="Returns the information of current weather",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=WeatherApiResponse::class))
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns error if request data is incorrect",
     *     )
     * )
     * @OA\Response(
     *     response=500,
     *     description="Error from 3rd party API",
     *     )
     * )
     * @OA\RequestBody(
     *     request="data",
     *     required=true,
     *     @OA\JsonContent(
     *     type="array",
     *         @OA\Items(
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="apiKey", type="string"),
     *         ),
     *     )
     * )
     * @OA\Parameter(
     *     name="data",
     *     in="path",
     *     description="Array to put request params",
     *     @OA\Schema(
     *     type="array",
     *              @OA\Items(
     *                  @OA\Property(property="city", type="string"),
     *                  @OA\Property(property="apiKey", type="string"),
     *              ),
     *      )
     * )
     */
    public function getWeather(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //Checks if data is valid
        if (!$data['city'] or !$data['apiKey'] or preg_match('~[0-9]+~', $data['city'])) {
            return new JsonResponse('Incorrect data', Response::HTTP_BAD_REQUEST);
        }

        //Api data fetching
        $openWeatherApi = new WeatherApiRequest(
            'https://api.openweathermap.org/data/2.5/weather?',
            'query',
            new RequestParam('q', $data['city']),
            new RequestParam('appid', $data['apiKey'])
        );

        //Calls 3rd party api for data
        $weatherInfo = $openWeatherApi->fetchWeatherApi();

        //Formats a new data format for return
        $croppedData = $openWeatherApi->formatResponseData($weatherInfo);

        return new JsonResponse(json_decode($croppedData->formatToJson($croppedData)), Response::HTTP_OK);
    }

}
