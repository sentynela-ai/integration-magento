<?php

namespace Sentynela\FraudDetector\Model\Delivery;

/**
 * Class DeliveryEntry
 * @package Sentynela\FraudDetector\Model\Delivery
 * @author Jean Poffo
 */
class DeliveryEntry
{

    /** @var integer */
    private $deliveryId;

    /** @var float */
    private $freightValue;

    /** @var float */
    private $freightValueCompany;

    /** @var integer */
    private $termWorkingDays;

    /** @var string */
    private $service;

    /** @var string */
    private $company;

    /**
     * @return int
     */
    public function getDeliveryId(): int
    {
        return $this->deliveryId;
    }

    /**
     * @param int $deliveryId
     */
    public function setDeliveryId(int $deliveryId): void
    {
        $this->deliveryId = $deliveryId;
    }

    /**
     * @return float
     */
    public function getFreightValue(): float
    {
        return $this->freightValue;
    }

    /**
     * @param float $freightValue
     */
    public function setFreightValue(float $freightValue): void
    {
        $this->freightValue = $freightValue;
    }

    /**
     * @return float
     */
    public function getFreightValueCompany(): float
    {
        return $this->freightValueCompany;
    }

    /**
     * @param float $freightValueCompany
     */
    public function setFreightValueCompany(float $freightValueCompany): void
    {
        $this->freightValueCompany = $freightValueCompany;
    }

    /**
     * @return int
     */
    public function getTermWorkingDays(): int
    {
        return $this->termWorkingDays;
    }

    /**
     * @param int $termWorkingDays
     */
    public function setTermWorkingDays(int $termWorkingDays): void
    {
        $this->termWorkingDays = $termWorkingDays;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService(string $service): void
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

}
