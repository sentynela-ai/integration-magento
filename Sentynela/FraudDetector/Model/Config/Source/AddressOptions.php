<?php

namespace Sentynela\FraudDetector\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class AddressOptions
 * @package Sentynela\FraudDetector\Model\Config\Source
 * @author Jean Poffo
 */
class AddressOptions implements OptionSourceInterface
{
    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => 1,
                'label' => __('Rua 1')
            ],
            [
                'value' => 2,
                'label' => __('Rua 2')
            ],
            [
                'value' => 3,
                'label' => __('Rua 3')
            ],
            [
                'value' => 4,
                'label' => __('Rua 4')
            ],
        ];
    }

}
