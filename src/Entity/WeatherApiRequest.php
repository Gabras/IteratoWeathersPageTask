<?php


namespace App\Entity;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherApiRequest
{
    private $url;
    private $dataTransferType;
    private $param1;
    private $param2;

    public function __construct($url, $dataTransferType, $param1, $param2)
    {
        $this->url = $url;
        $this->dataTransferType = $dataTransferType;
        $this->param1 = $param1;
        $this->param2 = $param2;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getDataTransferType()
    {
        return $this->dataTransferType;
    }

    public function setDataTransferType($dataTransferType): void
    {
        $this->dataTransferType = $dataTransferType;
    }

    public function getParam1()
    {
        return $this->param1;
    }

    public function setParam1($param1): void
    {
        $this->param1 = $param1;
    }

    public function getParam2()
    {
        return $this->param2;
    }

    public function setParam2($param2): void
    {
        $this->param2 = $param2;
    }

    public function fetchWeatherApi()
    {
        try {
            $response = HttpClient::create()->request('GET', $this->url, [
                $this->dataTransferType => [
                    $this->param1->getKey() => $this->param1->getValue(),
                    $this->param2->getKey() => $this->param2->getValue(),
                ],
            ]);
            return json_decode($response->getContent());
        } catch (TransportExceptionInterface $e) {
            return $e;
        } catch (ClientExceptionInterface $e) {
            return $e;
        } catch (RedirectionExceptionInterface $e) {
            return $e;
        } catch (ServerExceptionInterface $e) {
            return $e;
        }
    }

    public function cropResponseData($data)
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