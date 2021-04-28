<?php

namespace Sentynela\FraudDetector\Plugin;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\Order\Status\HistoryRepository;
use Psr\Log\LoggerInterface;
use Sentynela\FraudDetector\Builder\Checkout\Builders\BuilderCheckoutOrder;
use Sentynela\FraudDetector\Builder\Checkout\Director;
use Sentynela\FraudDetector\Helper\Connection;
use Sentynela\FraudDetector\Helper\Data;
use stdClass;

/**
 * Class OrderStatusChange
 * @package Sentynela\FraudDetector\Plugin
 * @author Jean Poffo
 */
class OrderStatusChange
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

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var OrderInterface */
    private $order;

    /** @var HistoryFactory */
    private $historyFactory;

    /** @var HistoryRepository */
    private $historyRepository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * AfterPlaceOrder constructor.
     * @param BuilderCheckoutOrder $builderCheckoutOrder
     * @param Connection $connection
     * @param Data $data
     * @param HistoryFactory $historyFactory
     * @param HistoryRepository $historyRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        BuilderCheckoutOrder $builderCheckoutOrder,
        Connection $connection,
        Data $data,
        HistoryFactory $historyFactory,
        HistoryRepository $historyRepository,
        LoggerInterface $logger
    ) {
        $this->builderCheckoutOrder = $builderCheckoutOrder;
        $this->connection = $connection;
        $this->data = $data;
        $this->historyRepository = $historyRepository;
        $this->historyFactory = $historyFactory;
        $this->logger = $logger;
    }

    private function isPaymentToAnalysis(): bool
    {
        return $this->data->getPaymentMethodAnalysis() === $this->order->getPayment()->getMethod();
    }

    private function isOrderToSendAnalysis(): bool
    {
        return $this->order->getStatus() === self::ORDER_STATUS_PROCESSING;
    }

    private function isOrderToUpdateStatus(): bool
    {
        return !array_contains([self::ORDER_STATUS_PENDENT, self::ORDER_STATUS_PROCESSING], $this->order->getStatus());
    }

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterSave(OrderRepositoryInterface $orderRepository, OrderInterface $order): OrderInterface
    {
        $this->orderRepository = $orderRepository;
        $this->order = $order;

        if (!$this->data->isPluginDesactive() && $this->isPaymentToAnalysis()) {
            if ($this->isOrderToSendAnalysis()) {
                $this->sendOrderToAnalysisSentynela();
            } elseif ($this->isOrderToUpdateStatus()) {
                $this->sendOrderToUpdateStatusSentynela();
            }
        }

        return $order;
    }

    private function sendOrderToAnalysisSentynela(): void
    {
        try {
            $this->builderCheckoutOrder->getFactory()->loadOrder($this->order);

            $director = new Director($this->builderCheckoutOrder);
            $director->make();

            $data = $this->builderCheckoutOrder->getCheckout();

            $responseString = $this->connection->createPost('predict', $data);

            $response = json_decode($responseString);

            if ($this->data->isPluginActive()) {
                if (isset($response->status) && $response->status === self::SENTYNELA_STATUS_SUCCESS) {
                    switch ($response->result) {
                        case self::SENTYNELA_STATUS_ORDER_REPROVED:
                            $this->order->setStatus(self::ORDER_STATUS_CANCELED);
                            $this->orderRepository->save($this->order);

                            $this->registryHistoryOnOrder('Sentynela - Order Reproved');
                            break;

                        case self::SENTYNELA_STATUS_ORDER_APPROVED:
                            $this->registryHistoryOnOrder('Sentynela - Order Approved');
                            break;
                    }
                } else {
                    $this->logger->error('Bad response on connect to Sentynela', [
                        'response' => $response,
                        'plugin' => 'Send Order to Analysis'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->logger->error('Error on connect to Sentynela', [
                'error' => $e,
                'plugin' => 'Send Order to Analysis'
            ]);
        }
    }

    private function sendOrderToUpdateStatusSentynela(): void
    {
        try {
            $orderStatus = new stdClass();
            $orderStatus->status = $this->order->getStatus();

            $data = new stdClass();
            $data->order = $orderStatus;

            $responseString = $this->connection->createPatch("orders/{$this->order->getIncrementId()}", $data);

            $response = json_decode($responseString);

            if (isset($response->status)) {
                if ($response->status === self::SENTYNELA_STATUS_NOT_FOUND) {
                    $this->builderCheckoutOrder->getFactory()->loadOrder($this->order);

                    $director = new Director($this->builderCheckoutOrder);
                    $director->make();

                    $checkout = $this->builderCheckoutOrder->getCheckout();

                    $this->connection->createPost('save', $checkout);
                }
            } elseif (!isset($response->order)) {
                $this->logger->error('Bad response on connect to Sentynela', [
                    'response' => $response,
                    'plugin' => 'Send Order to Update Status'
                ]);
            }
        } catch (Exception $e) {
            $this->logger->error('Error on connect to Sentynela', [
                'error' => $e,
                'plugin' => 'Send Order to Update Status'
            ]);
        }
    }

    /**
     * @throws CouldNotSaveException
     */
    private function registryHistoryOnOrder($description): void
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
