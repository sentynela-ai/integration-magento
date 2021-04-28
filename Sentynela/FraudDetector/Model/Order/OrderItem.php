<?php

namespace Sentynela\FraudDetector\Model\Order;

/**
 * Class OrderItem
 * @package Sentynela\FraudDetector\Model\Order
 * @author Jean Poffo
 */
class OrderItem
{

    /** @var string */
    private $sku;

    /** @var string */
    private $description;

    /** @var integer */
    private $quantity;

    /** @var float */
    private $totalValue;

    /** @var integer */
    private $deliveryId;

    /** @var OrderItemCategory[] */
    private $categories;

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
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
     * @return OrderItemCategory[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param OrderItemCategory[] $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @param OrderItemCategory $orderItemCategory
     */
    public function addCategory(OrderItemCategory $orderItemCategory): void
    {
        $this->categories[] = $orderItemCategory;
    }

    /**
     * @param OrderItemCategory $orderItemCategory
     */
    public function removeCategory(OrderItemCategory $orderItemCategory): void
    {
        $this->categories = array_filter($this->categories, function ($actualOrderItemCategory) use ($orderItemCategory) {
            return $orderItemCategory !== $actualOrderItemCategory;
        });
    }
}
