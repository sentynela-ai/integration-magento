<?php

namespace Sentynela\FraudDetector\Plugin;

use Psr\Log\LoggerInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\Order\Status\HistoryRepository;
use Sentynela\FraudDetector\Builder\Checkout\Builders\BuilderCheckoutOrder;
use Sentynela\FraudDetector\Helper\Connection;
use Sentynela\FraudDetector\Helper\Data;

/**
 * Class PluginBase
 * @package Sentynela\FraudDetector\Plugin
 * @author Jean Poffo
 */
abstract class PluginBase
{

    const ORDER_STATUS_PENDENT = 'pending';
    const ORDER_STATUS_PROCESSING = 'processing';
    const ORDER_STATUS_CANCELED = 'canceled';

    const SENTYNELA_STATUS_NOT_FOUND = 'not_found';
    const SENTYNELA_STATUS_SUCCESS = 'success';

    const SENTYNELA_STATUS_ORDER_REPROVED = 'not_analysed';
    const SENTYNELA_STATUS_ORDER_APPROVED = 'approved';

    /** @var BuilderCheckoutOrder */
    protected $builderCheckoutOrder;

    /** @var Connection */
    protected $connection;

    /** @var Data */
    protected $data;

    /** @var OrderInterface */
    protected $order;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var OrderManagementInterface */
    protected $orderManagement;

    /** @var HistoryFactory */
    protected $historyFactory;

    /** @var HistoryRepository */
    protected $historyRepository;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * PluginBase constructor.
     * @param BuilderCheckoutOrder $builderCheckoutOrder
     * @param Connection $connection
     * @param Data $data
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderManagementInterface $orderManagement
     * @param HistoryRepository $historyRepository
     * @param HistoryFactory $historyFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        BuilderCheckoutOrder $builderCheckoutOrder,
        Connection $connection,
        Data $data,
        OrderRepositoryInterface $orderRepository,
        OrderManagementInterface $orderManagement,
        HistoryRepository $historyRepository,
        HistoryFactory $historyFactory,
        LoggerInterface $logger
    ) {
        $this->builderCheckoutOrder = $builderCheckoutOrder;
        $this->connection = $connection;
        $this->data = $data;
        $this->orderRepository = $orderRepository;
        $this->orderManagement = $orderManagement;
        $this->historyRepository = $historyRepository;
        $this->historyFactory = $historyFactory;
        $this->logger = $logger;
    }

    protected function isPluginActive(): bool
    {
        return $this->data->isPluginActive();
    }

    protected function isPluginDeactivated(): bool
    {
        return $this->data->isPluginDeactivated();
    }

    protected function isPluginSandbox(): bool
    {
        return $this->data->isPluginSandbox();
    }

    protected function getStatusAfterReprove(): string
    {
        return $this->data->getStatusAfterReprove();
    }

    protected function isStatusAfterReproveCancel(): bool
    {
        return $this->data->getStatusAfterReprove() === self::ORDER_STATUS_CANCELED;
    }

    protected function isPaymentToAnalysis(): bool
    {
        return $this->data->getPaymentMethodAnalysis() === $this->order->getPayment()->getMethod();
    }

    protected function isOrderToSendAnalysis(): bool
    {
        return $this->order->getStatus() === self::ORDER_STATUS_PROCESSING;
    }

    protected function isOrderToUpdateStatus(): bool
    {
        return !array_contains([
            self::ORDER_STATUS_PENDENT,
            self::ORDER_STATUS_PROCESSING
        ], $this->order->getStatus());
    }

    /**
     * @throws CouldNotSaveException
     */
    protected function registryHistoryOnOrder($description): void
    {
        $history = $this->historyFactory->create()
            ->setParentId($this->order->getEntityId())
            ->setStatus($this->order->getStatus())
            ->setIsCustomerNotified(false)
            ->setIsVisibleOnFront(false)
            ->setComment($description);

        $this->historyRepository->save($history);
    }

}
