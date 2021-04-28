<?php

namespace Sentynela\FraudDetector\Model\Customer;

/**
 * Class CustomerContact
 * @package Sentynela\FraudDetector\Model\Customer
 * @author Jean Poffo
 */
class CustomerContact
{

    /** @var string */
    private $contactType;

    /** @var string */
    private $contactCode;

    /**
     * @return string
     */
    public function getContactType(): string
    {
        return $this->contactType;
    }

    /**
     * @param string $contactType
     */
    public function setContactType(string $contactType): void
    {
        $this->contactType = $contactType;
    }

    /**
     * @return string
     */
    public function getContactCode(): string
    {
        return $this->contactCode;
    }

    /**
     * @param string $contactCode
     */
    public function setContactCode(string $contactCode): void
    {
        $this->contactCode = $contactCode;
    }

}
