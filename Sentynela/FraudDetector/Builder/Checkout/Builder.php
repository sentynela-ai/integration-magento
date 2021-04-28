<?php

namespace Sentynela\FraudDetector\Builder\Checkout;

use Sentynela\FraudDetector\Model\Checkout;

/**
 * Interface Builder
 * @package Sentynela\FraudDetector\Builder\Checkout
 * @author Jean Poffo
 */
interface Builder
{

    public function buildOrder(): void;

    public function buildCustomer(): void;

    public function buildPayment(): void;

    public function buildDelivery(): void;

    public function buildTracking(): void;

    public function getCheckout(): Checkout;

}
