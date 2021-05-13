<?php

namespace Sentynela\FraudDetector\Factory;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Sentynela\FraudDetector\Helper\Data;
use Sentynela\FraudDetector\Utils\ArrayUtils;

/**
 * Class FactoryOrder. To factory data of Order.
 * @package Sentynela\FraudDetector\Factory
 * @author Jean Poffo
 */
class FactoryOrderData
{

    /** @var CustomerRepositoryInterface */
    protected $customerRepository;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var CategoryRepositoryInterface */
    protected $categoryRepository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var Http */
    protected $request;

    /** @var Data */
    protected $data;

    /** @var OrderInterface */
    private $order;

    /** @var CustomerInterface */
    private $customer;

    /**
     * @var CustomerMetadataInterface
     */
    protected $customerMetadata;

    /** @var OrderPaymentInterface */
    private $payment;

    /**
     * FactoryOrderData constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Http $request
     * @param Data $data
     * @param CustomerMetadataInterface $customerMetadata
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Http $request,
        Data $data,
        CustomerMetadataInterface $customerMetadata
    ) {
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->request = $request;
        $this->data = $data;
        $this->customerMetadata = $customerMetadata;
    }

    /**
     * @return Data
     */
    public function getData(): Data
    {
        return $this->data;
    }

    /**
     * @param OrderInterface $order
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function loadOrder(OrderInterface $order)
    {
        $this->order = $order;
        $this->customer = $this->customerRepository->getById($order->getCustomerId());
        $this->payment = $order->getPayment();
    }

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer(): CustomerInterface
    {
        return $this->customer;
    }

    /**
     * @return OrderPaymentInterface
     */
    public function getPayment(): OrderPaymentInterface
    {
        return $this->payment;
    }

    /**
     * @return Http
     */
    public function getRequest(): Http
    {
        return $this->request;
    }

    /**
     * Calculates the total number of canceled Customer Orders in the last 24 hours
     * @return int
     */
    public function getCustomerQuantityCanceledLastDay(): int
    {
        $customerId = $this->customer->getId();
        $endDatetime = date('Y-m-d H:i:s', strtotime($this->order->getCreatedAt()));
        $startDatetime = date('Y-m-d H:i:s', strtotime("{$this->order->getCreatedAt()} -1 day"));

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_id', $customerId)
            ->addFilter('created_at', $startDatetime, 'gteq')
            ->addFilter('created_at', $endDatetime, 'lteq')
            ->addFilter('status', ['fraud', 'canceled'], 'in')
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        return count($orders);
    }

    /**
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomerGender(): string
    {
        $id = $this->customer->getGender();
        $options = $this->customerMetadata->getAttributeMetadata('gender')->getOptions();

        if (array_key_exists($id, $options)) {
            return $options[$id]->getLabel();
        }

        return '';
    }

    public function getCustomerAddress(): AddressInterface
    {
        $customerAddressesInterface = array_filter($this->customer->getAddresses(), function ($address) {
            return $address->isDefaultShipping();
        });

        return array_shift($customerAddressesInterface);
    }

    public function getCustomerAddressStreet(): string
    {
        $customerAddress = $this->getCustomerAddress();

        if ($customerAddress) {
            return ArrayUtils::getValueKeyChecked($this->data->getAddressStreetLine(), $customerAddress->getStreet());
        }

        return '';
    }

    public function getCustomerAddressNumber(): int
    {
        $customerAddress = $this->getCustomerAddress();

        if ($customerAddress) {
            return (int) ArrayUtils::getValueKeyChecked($this->data->getAddressNumberLine(), $customerAddress->getStreet());
        }

        return 0;
    }

    public function getCustomerAddressComplement(): string
    {
        $customerAddress = $this->getCustomerAddress();

        if ($customerAddress) {
            return ArrayUtils::getValueKeyChecked($this->data->getAddressComplementLine(), $customerAddress->getStreet());
        }

        return '';
    }

    public function getCustomerAddressNeighborhood(): string
    {
        $customerAddress = $this->getCustomerAddress();

        if ($customerAddress) {
            return ArrayUtils::getValueKeyChecked($this->data->getAddressNeighborhoodLine(), $customerAddress->getStreet());
        }

        return '';
    }

    /**
     * @param string $productId
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    public function getCategoryFromProductId(string $productId): CategoryInterface
    {
        $product = $this->productRepository->getById($productId);

        $categoriesLink = $product->getExtensionAttributes()->getCategoryLinks();
        $categoryLink = array_pop($categoriesLink);

        return $this->categoryRepository->get($categoryLink->getCategoryId());
    }

    public function getCreditCardMethod(): string
    {
        $additionalInformation = $this->order->getPayment()->getAdditionalInformation();

        return ArrayUtils::getValueKeyChecked('cc_type', $additionalInformation);
    }

    public function getCreditCardTotalValue(): float
    {
        $ordered = $this->getOrder()->getPayment()->getAmountOrdered();
        $shipping = $this->getOrder()->getPayment()->getShippingAmount();

        return $ordered - $shipping;
    }

    public function getOrderShippingService(): string
    {
        $shipping = explode('-', $this->order->getShippingDescription());

        return trim(array_pop($shipping));
    }

    public function getOrderShippingCompany(): string
    {
        $shipping = explode('-', $this->order->getShippingDescription());

        return trim(array_shift($shipping));
    }

    public function getCookieFingerprint(): string
    {
        return $this->request->getCookie('_sen', '');
    }

    public function getCookieTimestamp(): int
    {
        $cookie = $this->getCookieFingerprint();

        if ($cookie) {
            $cookieTimestamp = (int) substr($cookie, 2, 13);

            return round($cookieTimestamp / 1000);
        }

        return 0;
    }

}
