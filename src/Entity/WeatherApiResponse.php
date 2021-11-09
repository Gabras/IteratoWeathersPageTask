<?php


namespace App\Entity;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use OpenApi\Annotations as OA;

class WeatherApiResponse
{
    /**
     * @OA\Property(type="string", maxLength=255)
     */
    private string $city;
    /**
     * @OA\Property(type="float")
     */
    private float $minTemp;
    /**
     * @OA\Property(type="float")
     */
    private float $maxTemp;
    /**
     * @OA\Property(type="float")
     */
    private float $currentTemp;
    /**
     * @OA\Property(type="string", maxLength=255)
     */
    private string $weatherDescription;
    /**
     * @OA\Property(type="float")
     */
    private float $windSpeed;

    public function __construct(string $city, float $minTemp, float $maxTemp, float $currentTemp, string $weatherDescription, float $windSpeed)
    {
        $this->city = $city;
        $this->minTemp = $minTemp;
        $this->maxTemp = $maxTemp;
        $this->currentTemp = $currentTemp;
        $this->weatherDescription = $weatherDescription;
        $this->windSpeed = $windSpeed;
    }

    public function formatToJson(WeatherApiResponse $data)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($data, 'json');
    }

    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }

    public function getWeatherDescription(): string
    {
        return $this->weatherDescription;
    }

    public function getCurrentTemp(): float
    {
        return $this->currentTemp;
    }

    public function getMaxTemp(): float
    {
        return $this->maxTemp;
    }

    public function getMinTemp(): float
    {
        return $this->minTemp;
    }

    public function getCity(): string
    {
        return $this->city;
    }
}