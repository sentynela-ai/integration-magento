<?php

namespace Sentynela\FraudDetector\Model\Payment;

use Sentynela\FraudDetector\Model\Serializable;

/**
 * Class Payment
 * @package Sentynela\FraudDetector\Model\Payment
 * @author Jean Poffo
 */
class Payment extends Serializable
{
    /** @var PaymentEntry[] */
    private $entries;

    /**
     * @return PaymentEntry[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param PaymentEntry[] $entries
     */
    public function setEntries(array $entries): void
    {
        $this->entries = $entries;
    }

    /**
     * @param PaymentEntry $paymentEntry
     */
    public function addEntry(PaymentEntry $paymentEntry): void
    {
        $this->entries[] = $paymentEntry;
    }

    /**
     * @param PaymentEntry $paymentEntry
     */
    public function removeEntry(PaymentEntry $paymentEntry): void
    {
        $this->entries = array_filter($this->entries, function ($actualPaymentEntry) use ($paymentEntry) {
            return $actualPaymentEntry !== $paymentEntry;
        });
    }
}
