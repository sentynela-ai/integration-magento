<?php

namespace Sentynela\FraudDetector\Model;

use JsonSerializable;
use ReflectionClass;
use Sentynela\FraudDetector\Utils\StringUtils;

/**
 * Class Serializable. Implements reflection to create a beautiful JSON.
 * @package Sentynela\FraudDetector\Model
 * @author Jean Poffo
 */
abstract class Serializable implements JsonSerializable
{

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->reflect($this);
    }

    /**
     * @param $instance
     * @return array
     * @ensures Instance does not have recursive properties
     */
    private function reflect($instance): array
    {
        $reflection = new ReflectionClass($instance);

        $properties = [];

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);

            $propertyValue = $property->getValue($instance);

            switch (true) {
                case is_array($propertyValue):
                    $value = [];

                    foreach ($propertyValue as $propertyInstance) {
                        $value[] = $this->reflect($propertyInstance);
                    }
                    break;

                case is_object($propertyValue):
                    $value = $this->reflect($propertyValue);
                    break;

                default:
                    $value = $propertyValue;
            }

            /** Allow 0, but not null */
            if (!is_numeric($value) && !$value) {
                continue;
            }

            $propertyName = StringUtils::camelCaseToSnakeCase($property->getName());
            $properties[$propertyName] = $value;
        }

        return $properties;
    }

}
