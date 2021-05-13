<?php

namespace Sentynela\FraudDetector\Block;

use Magento\CatalogSearch\Helper\Data as CatalogSearchData;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use Magento\Store\Model\ScopeInterface;
use Sentynela\FraudDetector\Helper\Data;

/**
 * Class FingerprintData. Custom data for use on Fingerprint plugin.
 * @package Sentynela\FraudDetector\Helper
 * @author Jean Poffo
 */
class FingerprintData extends Template
{
    /** @var CustomerSession */
    protected $customerSession;

    /** @var CheckoutSession */
    protected $checkoutSession;

    /** @var CatalogSearchData */
    protected $catalogSearchData;

    /** @var Order */
    private $order;

    /**
     * FingerprintData constructor.
     * @param CustomerSession $customerSession
     * @param CheckoutSession $checkoutSession
     * @param CatalogSearchData $catalogSearchData
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        CatalogSearchData $catalogSearchData,
        Context $context,
        $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->catalogSearchData = $catalogSearchData;
        $this->checkoutSession = $checkoutSession;
        $this->order = $checkoutSession->getLastRealOrder();

        parent::__construct(
            $context,
            $data
        );
    }

    public function isPluginActive(): bool
    {
        return in_array(
            $this->_scopeConfig->getValue(Data::PLUGIN_ACTIVE, ScopeInterface::SCOPE_STORE),
            [
                Data::PLUGIN_ACTIVE_OPTION_ACTIVE,
                Data::PLUGIN_ACTIVE_OPTION_SANDBOX
            ]
        );
    }

    public function getStoreCode(): string
    {
        return (string) $this->_scopeConfig->getValue(Data::STORE_ID, ScopeInterface::SCOPE_STORE);
    }

    public function getSearchTerm(): string
    {
        return $this->catalogSearchData->getEscapedQueryText();
    }

    public function getOrderId(): string
    {
        return (string) $this->order->getIncrementId();
    }

    /**
     * @todo Some day, remove Object Manager...
     * @return string
     */
    public function getCustomerId(): string
    {
        $customerSession = ObjectManager::getInstance()
            ->create('Magento\Customer\Model\SessionFactory')
            ->create();

        return (string) $customerSession->getCustomer()->getId();
    }
}
