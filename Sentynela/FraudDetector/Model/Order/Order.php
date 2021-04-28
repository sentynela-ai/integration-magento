<?php

namespace Sentynela\FraudDetector\Model\Order;

/**
 * Class Order
 * @package Sentynela\FraudDetector\Model\Order
 * @author Jean Poffo
 */
class Order
{

    /** @var integer */
    private $id;

    /** @var string */
    private $code;

    /** @var string */
    private $datetime;

    /** @var string */
    private $status;

    /** @var float */
    private $productValue;

    /** @var float */
    private $totalValue;

    /** @var OrderItem[] */
    private $items;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
    }

    /**
     * @param string $datetime
     */
    public function setDatetime(string $datetime): void
    {
        $this->datetime = $datetime;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return float
     */
    public function getProductValue(): float
    {
        return $this->productValue;
    }

    /**
     * @param float $productValue
     */
    public function setProductValue(float $productValue): void
    {
        $this->productValue = $productValue;
    }

    /**
     * @return float
     */
    public function getTotalValue(): float
    {
        return $this->totalValue;
    }

    /**
     * @param float $totalValue
     */
    public function setTotalValue(float $totalValue): void
    {
        $this->totalValue = $totalValue;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param OrderItem[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function addItem(OrderItem $orderItem): void
    {
        $this->items[] = $orderItem;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function removeItem(OrderItem $orderItem): void
    {
        $this->items = array_filter($this->items, function ($actualOrderItem) use ($orderItem) {
            return $orderItem !== $actualOrderItem;
        });
    }

}
