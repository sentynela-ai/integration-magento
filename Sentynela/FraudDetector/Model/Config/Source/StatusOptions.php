<?php

namespace Sentynela\FraudDetector\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\Order\Config;

/**
 * Class StatusOptions
 * @package Sentynela\FraudDetector\Model\Config\Source
 * @author Jean Poffo
 */
class StatusOptions implements OptionSourceInterface
{

    /** @var  Config */
    protected $orderStatusData;

    /**
     * StatusOptions constructor.
     * @param Config $orderStatusData
     */
    public function __construct(Config $orderStatusData)
    {
        $this->orderStatusData = $orderStatusData;
    }

    public function toOptionArray(): array
    {
        $options = [];

        $status = $this->orderStatusData->getStatuses();

        foreach ($status as $code => $label) {
            $options [] = [
                'value' => $code,
                'label' => $label
            ];
        }

        return $options;
    }

}
