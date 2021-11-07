<?php


namespace App\Entity;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class WeatherApiResponse
{
    private $city;
    private $minTemp;
    private $maxTemp;
    private $currentTemp;
    private $weatherDescription;
    private $windSpeed;

    public function __construct($city, $minTemp, $maxTemp, $currentTemp, $weatherDescription, $windSpeed)
    {
        $this->city = $city;
        $this->minTemp = $minTemp;
        $this->maxTemp = $maxTemp;
        $this->currentTemp = $currentTemp;
        $this->weatherDescription = $weatherDescription;
        $this->windSpeed = $windSpeed;
    }

    public function setCity($city): void
    {
        $this->city = $city;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setMinTemp($minTemp): void
    {
        $this->minTemp = $minTemp;
    }

    public function getMinTemp(): float
    {
        return $this->minTemp;
    }

    public function setMaxTemp($maxTemp): void
    {
        $this->maxTemp = $maxTemp;
    }

    public function getMaxTemp(): float
    {
        return $this->maxTemp;
    }

    public function setCurrentTemp($currentTemp): void
    {
        $this->currentTemp = $currentTemp;
    }

    public function getCurrentTemp(): float
    {
        return $this->currentTemp;
    }

    public function setWeatherDescription($weatherDescription): void
    {
        $this->weatherDescription = $weatherDescription;
    }

    public function getWeatherDescription(): string
    {
        return $this->weatherDescription;
    }

    public function setWindSpeed($windSpeed): void
    {
        $this->windSpeed = $windSpeed;
    }

    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }

    public function formatToJson($data)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($data, 'json');
    }
}