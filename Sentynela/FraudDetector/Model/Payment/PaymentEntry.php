<?php

namespace Sentynela\FraudDetector\Model\Payment;

use Sentynela\FraudDetector\Model\Serializable;

/**
 * Class PaymentEntry
 * @package Sentynela\FraudDetector\Model\Payment
 * @author Jean Poffo
 */
class PaymentEntry extends Serializable
{

    /** @var string */
    private $method;

    /** @var string */
    private $conditionName;

    /** @var integer */
    private $installments;

    /** @var float */
    private $value;

    /** @var string */
    private $cardHolder;

    /** @var string */
    private $cardNumber;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getConditionName(): string
    {
        return $this->conditionName;
    }

    /**
     * @param string $conditionName
     */
    public function setConditionName(string $conditionName): void
    {
        $this->conditionName = $conditionName;
    }

    /**
     * @return int
     */
    public function getInstallments(): int
    {
        return $this->installments;
    }

    /**
     * @param int $installments
     */
    public function setInstallments(int $installments): void
    {
        $this->installments = $installments;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCardHolder(): string
    {
        return $this->cardHolder;
    }

    /**
     * @param string $cardHolder
     */
    public function setCardHolder(string $cardHolder): void
    {
        $this->cardHolder = $cardHolder;
    }

    /**
     * @return string
     */
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    /**
     * @param string $cardNumber
     */
    public function setCardNumber(string $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

}
