<?php

namespace Sentynela\FraudDetector\Plugin;

use Exception;
use Magento\Checkout\Model\PaymentInformationManagement;
use Sentynela\FraudDetector\Builder\Checkout\Director;

/**
 * Class OrderAfterPayment. Execute prediction after receive Payment.
 * @package Sentynela\FraudDetector\Plugin
 * @author Jean Poffo
 */
class OrderAfterPayment extends PluginBase
{

    /**
     * @param PaymentInformationManagement $subject
     * @param $orderId
     * @return string
     */
    public function afterSavePaymentInformationAndPlaceOrder(PaymentInformationManagement $subject, $orderId): string
    {
        $this->order = $this->orderRepository->get($orderId);

        if (!$this->isPluginDeactivated() && $this->isPaymentToAnalysis()) {
            $this->sendOrderToAnalysisSentynela();
        }


        return $orderId;
    }

    private function sendOrderToAnalysisSentynela(): void
    {
        try {
            $this->builderCheckoutOrder->getFactory()->loadOrder($this->order);

            $director = new Director($this->builderCheckoutOrder);
            $director->make();

            $data = $this->builderCheckoutOrder->getCheckout();

            $responseString = $this->connection->createPost('predict', $data);

            if (!$this->isPluginSandbox()) {
                $response = json_decode($responseString);

                if (isset($response->status) && $response->status === self::SENTYNELA_STATUS_SUCCESS) {
                    switch ($response->result) {
                        case self::SENTYNELA_STATUS_ORDER_REPROVED:
                            if ($this->isStatusAfterReproveCancel()) {
                                $this->orderManagement->cancel($this->order->getEntityId());
                            } else {
                                $this->order->setStatus($this->getStatusAfterReprove());
                                $this->orderRepository->save($this->order);
                            }

                            $this->registryHistoryOnOrder('Sentynela - Order Reproved');
                            break;

                        case self::SENTYNELA_STATUS_ORDER_APPROVED:
                            $this->order->setStatus($this->data->getStatusAfterApprove());
                            $this->orderRepository->save($this->order);

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

}
