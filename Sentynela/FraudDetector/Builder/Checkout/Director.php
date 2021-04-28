<?php

namespace Sentynela\FraudDetector\Builder\Checkout;

/**
 * Class Director. To construct Sentynela Checkout info.
 * @package Sentynela\FraudDetector\Builder\Checkout
 * @author Jean Poffo
 */
class Director
{

    /** @var Builder */
    private $builder;

    /**
     * Director constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param Builder $builder
     */
    public function setBuilder(Builder $builder): void
    {
        $this->builder = $builder;
    }

    public function make(): void
    {
        $this->builder->buildOrder();
        $this->builder->buildCustomer();
        $this->builder->buildPayment();
        $this->builder->buildDelivery();
        $this->builder->buildTracking();
    }

}
