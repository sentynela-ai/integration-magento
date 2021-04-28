<?php

namespace Sentynela\FraudDetector\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Sentynela\FraudDetector\Helper
 */
class Data
{

    const PLUGIN_ACTIVE_OPTION_ACTIVE = 1;
    const PLUGIN_ACTIVE_OPTION_SANDBOX = 2;
    const PLUGIN_ACTIVE_OPTION_DESACTIVE = 3;

    const PLUGIN_ACTIVE = 'section_settings_general/general_settings/plugin_active';

    const ADDRESS_STREET_LINE = 'section_settings_address/address_settings/street_line';
    const ADDRESS_NUMBER_LINE = 'section_settings_address/address_settings/number_line';
    const ADDRESS_COMPLEMENT_LINE = 'section_settings_address/address_settings/complement_line';
    const ADDRESS_NEIGHBORHOOD_LINE = 'section_settings_address/address_settings/neighborhood_line';

    const PAYMENT_METHOD_ANALYSIS = 'section_settings_payment/payment_settings/payment_method_analysis';

    const STORE_ID = 'section_settings_identification/identification_settings/store_id';
    const STORE_LOGIN = 'section_settings_identification/identification_settings/store_login';
    const STORE_PASSWORD = 'section_settings_identification/identification_settings/store_password';
    const STORE_URL_ANALYSIS = 'section_settings_identification/identification_settings/store_url_analysis';

    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getPluginActive(): int
    {
        return (int) $this->scopeConfig->getValue(self::PLUGIN_ACTIVE, ScopeInterface::SCOPE_STORE);
    }

    public function isPluginActive(): bool
    {
        return $this->getPluginActive() === self::PLUGIN_ACTIVE_OPTION_ACTIVE;
    }

    public function isPluginSandbox(): bool
    {
        return $this->getPluginActive() === self::PLUGIN_ACTIVE_OPTION_SANDBOX;
    }

    public function isPluginDesactive(): bool
    {
        return $this->getPluginActive() === self::PLUGIN_ACTIVE_OPTION_DESACTIVE;
    }

    public function getAddressStreetLine(): int
    {
        return (int) $this->scopeConfig->getValue(self::ADDRESS_STREET_LINE, ScopeInterface::SCOPE_STORE);
    }

    public function getAddressNumberLine(): int
    {
        return (int) $this->scopeConfig->getValue(self::ADDRESS_NUMBER_LINE, ScopeInterface::SCOPE_STORE);
    }

    public function getAddressComplementLine(): int
    {
        return (int) $this->scopeConfig->getValue(self::ADDRESS_COMPLEMENT_LINE, ScopeInterface::SCOPE_STORE);
    }

    public function getAddressNeighborhoodLine(): int
    {
        return (int) $this->scopeConfig->getValue(self::ADDRESS_NEIGHBORHOOD_LINE, ScopeInterface::SCOPE_STORE);
    }

    public function getPaymentMethodAnalysis(): string
    {
        return (string) $this->scopeConfig->getValue(self::PAYMENT_METHOD_ANALYSIS, ScopeInterface::SCOPE_STORE);
    }

    public function getStoreId(): string
    {
        return (string) $this->scopeConfig->getValue(self::STORE_ID, ScopeInterface::SCOPE_STORE);
    }

    public function getStoreLogin(): string
    {
        return (string) $this->scopeConfig->getValue(self::STORE_LOGIN, ScopeInterface::SCOPE_STORE);
    }

    public function getStorePassword(): string
    {
        return (string) $this->scopeConfig->getValue(self::STORE_PASSWORD, ScopeInterface::SCOPE_STORE);
    }

    public function getStoreUrlAnalysis(): string
    {
        return (string) $this->scopeConfig->getValue(self::STORE_URL_ANALYSIS, ScopeInterface::SCOPE_STORE);
    }

}
