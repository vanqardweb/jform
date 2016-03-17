<?php
namespace Vanqard\Jform;

class Field implements FieldInterface, \JsonSerializable
{
    private $fieldName;
    private $fieldValue;
    private $attributes = [];
    private $validators = [];

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function getName()
    {
        return $this->fieldName;
    }

    public function getValue()
    {
        return $this->fieldValue;
    }

    public function setValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;
    }

    public function addAttribute($attrKey, $attrValue)
    {
        $this->attributes[$attrKey] = $attrValue;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
        return $this;
    }

    public function getValidators()
    {
        return $this->validators;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'attributes' => $this->getAttributes(),
            'validators' => $this->getValidators()
        ];
    }

}
