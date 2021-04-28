<?php

namespace Sentynela\FraudDetector\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Payment\Helper\Data;

/**
 * Class PaymentOptions
 * @package Sentynela\FraudDetector\Model\Config\Source
 * @author Jean Poffo
 */
class PaymentOptions implements OptionSourceInterface
{

    /**
     * @var Data
     */
    protected $paymentData;

    /**
     * PaymentOptions constructor.
     * @param Data $paymentData
     */
    public function __construct(Data $paymentData)
    {
        $this->paymentData = $paymentData;
    }

    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        $options = [];

        $paymentMethods = $this->paymentData->getPaymentMethods();

        foreach ($paymentMethods as $code => $child) {
            if (array_key_exists('title', $child)) {
                $options [] = [
                    'value' => $code,
                    'label' => $child['title']
                ];
            }
        }

        return $options;
    }

}
