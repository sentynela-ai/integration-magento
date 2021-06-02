<?php

namespace Sentynela\FraudDetector\Plugin;

use Exception;
use stdClass;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Sentynela\FraudDetector\Builder\Checkout\Director;

/**
 * Class OrderStatusChange. Update status of Order on Sentynela.
 * @package Sentynela\FraudDetector\Plugin
 * @author Jean Poffo
 */
class OrderStatusChange extends PluginBase
{

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterSave(OrderRepositoryInterface $orderRepository, OrderInterface $order): OrderInterface
    {
        $this->orderRepository = $orderRepository;
        $this->order = $order;

        if (!$this->isPluginDeactivated() && $this->isPaymentToAnalysis()) {
            if ($this->isOrderToUpdateStatus()) {
                $this->sendOrderToUpdateStatusSentynela();
            }
        }

        return $order;
    }

    private function sendOrderToUpdateStatusSentynela(): void
    {
        try {
            $orderStatus = new stdClass();
            $orderStatus->status = $this->order->getStatus();

            $data = new stdClass();
            $data->order = $orderStatus;

            $responseString = $this->connection->createPatch("orders/{$this->order->getEntityId()}", $data);

            $response = json_decode($responseString);

            if (!isset($response->order)) {
                if (isset($response->status) && $response->status === self::SENTYNELA_STATUS_NOT_FOUND) {
                    $this->builderCheckoutOrder->getFactory()->loadOrder($this->order);

                    $director = new Director($this->builderCheckoutOrder);
                    $director->make();

                    $checkout = $this->builderCheckoutOrder->getCheckout();

                    $this->connection->createPost('save', $checkout);
                }
                else {
                    $this->logger->error('Bad response on connect to Sentynela', [
                        'response' => json_encode($response),
                        'plugin' => 'Send Order to Update Status'
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->logger->error('Error on connect to Sentynela', [
                'error' => $e,
                'plugin' => 'Send Order to Update Status'
            ]);
        }
    }

}
