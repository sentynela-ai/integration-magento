<?php

namespace Sentynela\FraudDetector\Model\Customer;

/**
 * Class Customer
 * @package Sentynela\FraudDetector\Model\Customer
 * @author Jean Poffo
 */
class Customer
{

    /** @var integer */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $doc;

    /** @var string */
    private $email;

    /** @var string */
    private $gender;

    /** @var string */
    private $birthDate;

    /** @var string */
    private $registerDatetime;

    /** @var integer */
    private $qtCancelled24h;

    /** @var CustomerAddress */
    private $address;

    /** @var CustomerContact[] */
    private $contacts;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->address = new CustomerAddress();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDoc(): string
    {
        return $this->doc;
    }

    /**
     * @param string $doc
     */
    public function setDoc(string $doc): void
    {
        $this->doc = $doc;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    /**
     * @param string $birthDate
     */
    public function setBirthDate(string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getRegisterDatetime(): string
    {
        return $this->registerDatetime;
    }

    /**
     * @param string $registerDatetime
     */
    public function setRegisterDatetime(string $registerDatetime): void
    {
        $this->registerDatetime = $registerDatetime;
    }

    /**
     * @return int
     */
    public function getQtCancelled24h(): int
    {
        return $this->qtCancelled24h;
    }

    /**
     * @param int $qtCancelled24h
     */
    public function setQtCancelled24h(int $qtCancelled24h): void
    {
        $this->qtCancelled24h = $qtCancelled24h;
    }

    /**
     * @return CustomerAddress
     */
    public function getAddress(): CustomerAddress
    {
        return $this->address;
    }

    /**
     * @param CustomerAddress $address
     */
    public function setAddress(CustomerAddress $address): void
    {
        $this->address = $address;
    }

    /**
     * @return CustomerContact[]
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * @param CustomerContact[] $contacts
     */
    public function setContacts(array $contacts): void
    {
        $this->contacts = $contacts;
    }

    /**
     * @param CustomerContact $contact
     */
    public function addContact(CustomerContact $contact): void
    {
        $this->contacts[] = $contact;
    }

    /**
     * @param CustomerContact $contact
     */
    public function removeContact(CustomerContact $contact): void
    {
        $this->contacts = array_filter($this->contacts, function(CustomerContact $actualContact) use ($contact) {
            return $actualContact !== $contact;
        });
    }

}
