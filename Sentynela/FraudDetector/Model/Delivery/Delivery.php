<?php

namespace Sentynela\FraudDetector\Model\Delivery;

/**
 * Class Delivery
 * @package Sentynela\FraudDetector\Model\Delivery
 * @author Jean Poffo
 */
class Delivery
{

    /** @var DeliveryEntry[] */
    private $entries;

    /**
     * @return DeliveryEntry[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param DeliveryEntry[] $entries
     */
    public function setEntries(array $entries): void
    {
        $this->entries = $entries;
    }

    /**
     * @param DeliveryEntry $deliveryEntry
     */
    public function addEntry(DeliveryEntry $deliveryEntry): void
    {
        $this->entries[] = $deliveryEntry;
    }

    /**
     * @param DeliveryEntry $deliveryEntry
     */
    public function removeEntry(DeliveryEntry $deliveryEntry): void
    {
        $this->entries = array_filter($this->entries, function ($actualDeliveryEntry) use ($deliveryEntry) {
            return $deliveryEntry !== $actualDeliveryEntry;
        });
    }
}
