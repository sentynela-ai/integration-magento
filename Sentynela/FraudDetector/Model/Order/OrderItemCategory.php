<?php

namespace Sentynela\FraudDetector\Model\Order;

/**
 * Class OrderItemCategory
 * @package Sentynela\FraudDetector\Model\Order
 * @author Jean Poffo
 */
class OrderItemCategory
{

    /** @var string */
    private $categoryName;

    /** @var integer */
    private $level;

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @param string $categoryName
     */
    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

}
