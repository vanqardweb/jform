<?php
namespace Vanqard\Jform;

class FieldCollection extends \ArrayIterator implements \JsonSerializable
{
    public function addField(FieldInterface $field)
    {
        $fieldName = $field->getName();

        $this->offsetSet($fieldName, $field);
    }

    public function getFields()
    {
        $returnArray = [];

        foreach ($this as $fieldName => $field) {
            $returnArray[$fieldName] = $field;
        }

        return $returnArray;
    }

    public function jsonSerialize()
    {
        $returnArray = [];

        $fields = $this->getFields();

        foreach ($fields as $field) {
            $returnArray[] = $field->jsonSerialize();
        }

        return $returnArray;
    }
}
