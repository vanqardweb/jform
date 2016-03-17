<?php
namespace Vanqard\Jform;

class Form implements \JsonSerializable
{
    private $schemaName;
    private $meta = [];
    private $fieldCollection;
    private $fieldMask = [];

    public function __construct($schemaName)
    {
        $this->schemaName = $schemaName;
        $this->fieldCollection = new FieldCollection();
    }

    public function addMeta($metaKey, $metaValue)
    {
        $this->meta[$metaKey] = $metaValue;
        return $this;
    }

    /**
     * @param $metaKey
     * @param string|null $default
     * @return string|null
     */
    public function getMeta($metaKey, $default = null)
    {
        $returnValue = $default;
        if (array_key_exists($metaKey, $this->meta)) {
            $returnValue = $this->meta[$metaKey];
        }

        return $returnValue;
    }

    public function getMetaAll()
    {
        return (array)$this->meta;
    }

    public function addField(FieldInterface $field)
    {
        if (!$this->isMaskedField($field)) {

            $this->fieldCollection->addField($field);
        }

        return $this;
    }

    public function getFields()
    {
        return (array) $this->fieldCollection->jsonSerialize();
    }

    public function isMaskedField(Field $field)
    {
        $returnValue = false;
        $fieldName = $field->getName();

        if (array_key_exists($fieldName, $this->fieldMask)) {
            $returnValue = true;
        }

        return $returnValue;
    }

    public function jsonSerialize()
    {
        return [
            'schema' => $this->schemaName,
            'meta' => $this->getMetaAll(),
            'fields' => $this->getFields()
        ];
    }


    /**
     * @param string $fieldName
     * @return Form $this
     */
    public function addFieldMask($fieldName)
    {
        $fieldName = trim($fieldName);
        $this->fieldMask[$fieldName] = true;

        return $this;
    }

    /**
     * @param string $fieldName
     * @return $this
     */
    public function removeFieldMask($fieldName)
    {
        $fieldName = trim($fieldName);
        if(array_key_exists($fieldName, $this->fieldMask)) {
            unset($this->fieldMask[$fieldName]);
        }

        return $this;
    }
}

