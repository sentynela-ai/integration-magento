<?php

namespace Sentynela\FraudDetector\Model;

use Sentynela\FraudDetector\Model\Customer\Customer;
use Sentynela\FraudDetector\Model\Delivery\Delivery;
use Sentynela\FraudDetector\Model\Order\Order;
use Sentynela\FraudDetector\Model\Payment\Payment;
use Sentynela\FraudDetector\Model\Tracking\Tracking;

/**
 * Class Checkout
 * @package Sentynela\FraudDetector\Model
 * @author Jean Poffo
 */
class Checkout extends Serializable
{

    /** @var Order */
    private $order;

    /** @var Customer */
    private $customer;

    /** @var Payment */
    private $payment;

    /** @var Delivery */
    private $delivery;

    /** @var Tracking */
    private $tracking;

    /**
     * Checkout constructor.
     */
    public function __construct()
    {
        $this->order = new Order();
        $this->customer = new Customer();
        $this->payment = new Payment();
        $this->delivery = new Delivery();
        $this->tracking = new Tracking();
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }

    /**
     * @return Delivery
     */
    public function getDelivery(): Delivery
    {
        return $this->delivery;
    }

    /**
     * @param Delivery $delivery
     */
    public function setDelivery(Delivery $delivery): void
    {
        $this->delivery = $delivery;
    }

    /**
     * @return Tracking
     */
    public function getTracking(): Tracking
    {
        return $this->tracking;
    }

    /**
     * @param Tracking $tracking
     */
    public function setTracking(Tracking $tracking): void
    {
        $this->tracking = $tracking;
    }

}
