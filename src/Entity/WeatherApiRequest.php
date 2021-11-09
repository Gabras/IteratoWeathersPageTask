<?php


namespace App\Entity;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class WeatherApiRequest
{

    private string $url;
    private string $dataTransferType;
    private RequestParam $param1;
    private RequestParam $param2;

    public function __construct(string $url, string $dataTransferType, RequestParam $param1, RequestParam $param2)
    {
        $this->url = $url;
        $this->dataTransferType = $dataTransferType;
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    public function fetchWeatherApi(): object
    {
        try {
            $response = HttpClient::create()->request('GET', $this->url, [
                $this->dataTransferType => [
                    $this->param1->getKey() => $this->param1->getValue(),
                    $this->param2->getKey() => $this->param2->getValue(),
                ],
            ]);
            return json_decode($response->getContent());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function formatResponseData(object $data): WeatherApiResponse
    {
        return new WeatherApiResponse(
            $data->name,
            $data->main->temp_min,
            $data->main->temp_max,
            $data->main->temp,
            $data->weather[0]->description,
            $data->wind->speed
        );
    }


}