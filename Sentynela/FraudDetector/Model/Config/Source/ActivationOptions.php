<?php

namespace Sentynela\FraudDetector\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class ActivationOptions
 * @package Sentynela\FraudDetector\Model\Config\Source
 * @author Jean Poffo
 */
class ActivationOptions implements OptionSourceInterface
{

    public function toOptionArray(): array
    {
        return [
            [
                'value' => 1,
                'label' => __('Ativado')
            ],
            [
                'value' => 2,
                'label' => __('Sandbox')
            ],
            [
                'value' => 3,
                'label' => __('Desativado')
            ]
        ];
    }

}
