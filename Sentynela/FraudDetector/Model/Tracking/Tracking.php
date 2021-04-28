<?php

namespace Sentynela\FraudDetector\Model\Tracking;

use Sentynela\FraudDetector\Model\Serializable;

/**
 * Class Tracking
 * @package Sentynela\FraudDetector\Model\Tracking
 * @author Jean Poffo
 */
class Tracking extends Serializable
{

    /** @var string */
    private $ip;

    /** @var string */
    private $agent;

    /** @var string */
    private $utmSource;

    /** @var string */
    private $utmMedium;

    /** @var string */
    private $utmCampaign;

    /** @var integer */
    private $startBuy;

    /** @var string */
    private $cookieSen;

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getAgent(): string
    {
        return $this->agent;
    }

    /**
     * @param string $agent
     */
    public function setAgent(string $agent): void
    {
        $this->agent = $agent;
    }

    /**
     * @return string
     */
    public function getUtmSource(): string
    {
        return $this->utmSource;
    }

    /**
     * @param string $utmSource
     */
    public function setUtmSource(string $utmSource): void
    {
        $this->utmSource = $utmSource;
    }

    /**
     * @return string
     */
    public function getUtmMedium(): string
    {
        return $this->utmMedium;
    }

    /**
     * @param string $utmMedium
     */
    public function setUtmMedium(string $utmMedium): void
    {
        $this->utmMedium = $utmMedium;
    }

    /**
     * @return string
     */
    public function getUtmCampaign(): string
    {
        return $this->utmCampaign;
    }

    /**
     * @param string $utmCampaign
     */
    public function setUtmCampaign(string $utmCampaign): void
    {
        $this->utmCampaign = $utmCampaign;
    }

    /**
     * @return int
     */
    public function getStartBuy(): int
    {
        return $this->startBuy;
    }

    /**
     * @param int $startBuy
     */
    public function setStartBuy(int $startBuy): void
    {
        $this->startBuy = $startBuy;
    }

    /**
     * @return string
     */
    public function getCookieSen(): string
    {
        return $this->cookieSen;
    }

    /**
     * @param string $cookieSen
     */
    public function setCookieSen(string $cookieSen): void
    {
        $this->cookieSen = $cookieSen;
    }

}
