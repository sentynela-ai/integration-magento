<?php

namespace Sentynela\FraudDetector\Builder\Checkout\Builders;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Sentynela\FraudDetector\Builder\Checkout\Builder;
use Sentynela\FraudDetector\Factory\FactoryOrderData;
use Sentynela\FraudDetector\Model\Checkout;
use Sentynela\FraudDetector\Model\Customer\CustomerContact;
use Sentynela\FraudDetector\Model\Delivery\DeliveryEntry;
use Sentynela\FraudDetector\Model\Order\OrderItem;
use Sentynela\FraudDetector\Model\Order\OrderItemCategory;
use Sentynela\FraudDetector\Model\Payment\PaymentEntry;
use Sentynela\FraudDetector\Utils\StringUtils;

/**
 * Class BuilderCheckoutOrder
 * @package Sentynela\FraudDetector\Builder\Checkout\Builders
 * @author Jean Poffo
 */
class BuilderCheckoutOrder implements Builder
{

    /** @var FactoryOrderData */
    protected $factory;

    /** @var Http */
    protected $request;

    /** @var Checkout */
    protected $checkout;

    /**
     * BuilderCheckoutOrder constructor.
     * @param FactoryOrderData $factory
     * @param Checkout $checkout
     */
    public function __construct(FactoryOrderData $factory, Checkout $checkout)
    {
        $this->factory = $factory;
        $this->checkout = $checkout;
    }

    /**
     * @return FactoryOrderData
     */
    public function getFactory(): FactoryOrderData
    {
        return $this->factory;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function buildOrder(): void
    {
        $this->checkout->getOrder()->setId($this->factory->getOrder()->getEntityId());
        $this->checkout->getOrder()->setCode($this->factory->getOrder()->getEntityId());
        $this->checkout->getOrder()->setDatetime($this->factory->getOrder()->getCreatedAt());
        $this->checkout->getOrder()->setProductValue($this->factory->getOrder()->getGrandTotal() - $this->factory->getOrder()->getShippingAmount());
        $this->checkout->getOrder()->setTotalValue($this->factory->getOrder()->getGrandTotal());

        foreach ($this->factory->getOrder()->getItems() as $orderItemInterface) {
            $orderItem = new OrderItem();
            $orderItem->setSku($orderItemInterface->getSku());
            $orderItem->setDescription($orderItemInterface->getName());
            $orderItem->setQuantity($orderItemInterface->getQtyOrdered());
            $orderItem->setTotalValue($orderItemInterface->getPrice());
            $orderItem->setDeliveryId(0);

            $category = $this->factory->getCategoryFromProductId($orderItemInterface->getProductId());

            $orderItemCategory = new OrderItemCategory();
            $orderItemCategory->setCategoryName($category->getName());
            $orderItemCategory->setLevel($category->getLevel());

            $orderItem->addCategory($orderItemCategory);

            $this->checkout->getOrder()->addItem($orderItem);
        }
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function buildCustomer(): void
    {
        $name = StringUtils::createPhrase(
            $this->factory->getCustomer()->getFirstname(),
            $this->factory->getCustomer()->getMiddlename(),
            $this->factory->getCustomer()->getLastname()
        );

        $this->checkout->getCustomer()->setName($name);
        $this->checkout->getCustomer()->setId((int) $this->factory->getCustomer()->getId());
        $this->checkout->getCustomer()->setDoc((string) $this->factory->getCustomer()->getTaxvat());
        $this->checkout->getCustomer()->setEmail($this->factory->getCustomer()->getEmail());
        $this->checkout->getCustomer()->setGender($this->factory->getCustomerGender());
        $this->checkout->getCustomer()->setBirthDate((string) $this->factory->getCustomer()->getDob());
        $this->checkout->getCustomer()->setRegisterDatetime((string) $this->factory->getCustomer()->getCreatedAt());
        $this->checkout->getCustomer()->setQtCancelled24h($this->factory->getCustomerQuantityCanceledLastDay());

        if ($this->factory->getCustomerAddress()) {
            $customerName = StringUtils::createPhrase(
                $this->factory->getCustomerAddress()->getFirstname(),
                $this->factory->getCustomerAddress()->getMiddlename(),
                $this->factory->getCustomerAddress()->getLastname()
            );

            $this->checkout->getCustomer()->getAddress()->setRecipient($customerName);
            $this->checkout->getCustomer()->getAddress()->setStreet($this->factory->getCustomerAddressStreet());
            $this->checkout->getCustomer()->getAddress()->setStreetNumber($this->factory->getCustomerAddressNumber());
            $this->checkout->getCustomer()->getAddress()->setComplement($this->factory->getCustomerAddressComplement());
            $this->checkout->getCustomer()->getAddress()->setNeighborhood($this->factory->getCustomerAddressNeighborhood());
            $this->checkout->getCustomer()->getAddress()->setZipcode(StringUtils::toInteger($this->factory->getCustomerAddress()->getPostcode()));
            $this->checkout->getCustomer()->getAddress()->setCity((string) $this->factory->getCustomerAddress()->getCity());
            $this->checkout->getCustomer()->getAddress()->setState($this->factory->getCustomerAddress()->getRegion()->getRegion());

            $customerContact = new CustomerContact();
            $customerContact->setContactType('phone');
            $customerContact->setContactCode((string) $this->factory->getCustomerAddress()->getTelephone());

            $this->checkout->getCustomer()->addContact($customerContact);
        }
    }

    public function buildPayment(): void
    {
        $paymentEntry = new PaymentEntry();

        /**
         * @todo Condition Name. (Not Available)
         * @todo Payment Installments. (Not Available)

        $paymentEntry->setConditionName('');
        $paymentEntry->setInstallments(1);

         */

        $customerName = StringUtils::createPhrase(
            $this->factory->getOrder()->getBillingAddress()->getFirstname(),
            $this->factory->getOrder()->getBillingAddress()->getMiddlename(),
            $this->factory->getOrder()->getBillingAddress()->getLastname()
        );

        $paymentEntry->setCardHolder($customerName);
        $paymentEntry->setMethod($this->factory->getCreditCardMethod());
        $paymentEntry->setValue($this->factory->getCreditCardTotalValue());
        $paymentEntry->setCardNumber($this->factory->getOrder()->getPayment()->getCcLast4());

        $this->checkout->getPayment()->addEntry($paymentEntry);
    }

    public function buildDelivery(): void
    {
        $deliveryEntry = new DeliveryEntry();

        /**
         * @todo Delivery ID. (Not Available)
         * @todo Term Working Days. (Not Available)
         * @todo Freight Value of Company. Price Base? (Not Available)

        $deliveryEntry->setDeliveryId(0);
        $deliveryEntry->setTermWorkingDays(0);
        $deliveryEntry->setFreightValueCompany(0);

         */

        $deliveryEntry->setFreightValue((float) $this->factory->getOrder()->getShippingAmount());
        $deliveryEntry->setService($this->factory->getOrderShippingService());
        $deliveryEntry->setCompany($this->factory->getOrderShippingCompany());

        $this->checkout->getDelivery()->addEntry($deliveryEntry);
    }

    public function buildTracking(): void
    {
        /**
         * @todo UTM Informations. (Not Available)

        $this->checkout->getTracking()->setUtmSource($this->factory->getRequest()->getParam('utm_source', ''));
        $this->checkout->getTracking()->setUtmMedium($this->factory->getRequest()->getParam('utm_medium', ''));
        $this->checkout->getTracking()->setUtmCampaign($this->factory->getRequest()->getParam('utm_campaign', ''));

        */

        $this->checkout->getTracking()->setAgent($this->factory->getRequest()->getHeader('User-Agent', ''));
        $this->checkout->getTracking()->setIp($this->factory->getRequest()->getClientIp());
        $this->checkout->getTracking()->setStartBuy($this->factory->getCookieTimestamp());
        $this->checkout->getTracking()->setCookieSen($this->factory->getCookieFingerprint());
    }

    public function getCheckout(): Checkout
    {
        return $this->checkout;
    }
}
