<?php

namespace Sentynela\FraudDetector\Helper;

/**
 * Class Connection
 * @package Sentynela\FraudDetector\Factory
 * @author Jean Poffo
 */
class Connection
{

    /** @var Data */
    protected $data;

    /**
     * Connection constructor.
     * @param Data $data
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    /**
     * @param $route
     * @return string
     */
    private function getUrl($route): string
    {
        return "{$this->data->getStoreUrlAnalysis()}/{$route}";
    }

    /**
     * @return string
     */
    private function getAuth(): string
    {
        return "{$this->data->getStoreLogin()}:{$this->data->getStorePassword()}";
    }

    /**
     * @param string $route
     * @return false|resource
     */
    private function getCurlHandle(string $route)
    {
        $ch = curl_init($this->getUrl($route));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        return $ch;
    }

    /**
     * @param $ch
     * @return bool|string
     */
    private function executeCurlHandle($ch)
    {
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @param string $route
     * @param object $param
     * @return string
     */
    public function createPost(string $route, $param): string
    {
        $ch = $this->getCurlHandle($route);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->getAuth());

        return $this->executeCurlHandle($ch);
    }

    /**
     * @param string $route
     * @return string
     */
    public function createGet(string $route): string
    {
        $ch = $this->getCurlHandle($route);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->getAuth());

        return $this->executeCurlHandle($ch);
    }

    /**
     * @param string $route
     * @param object $param
     * @return string
     */
    public function createPut(string $route, $param): string
    {
        $ch = $this->getCurlHandle($route);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        curl_setopt($ch, CURLOPT_USERPWD, $this->getAuth());

        return $this->executeCurlHandle($ch);
    }

    /**
     * @param string $route
     * @param object $param
     * @return string
     */
    public function createPatch(string $route, $param): string
    {
        $ch = $this->getCurlHandle($route);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
        curl_setopt($ch, CURLOPT_USERPWD, $this->getAuth());

        return $this->executeCurlHandle($ch);
    }

    /**
     * @param string $route
     * @return string
     */
    public function createDelete(string $route): string
    {
        $ch = $this->getCurlHandle($route);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->getAuth());

        return $this->executeCurlHandle($ch);
    }
}
