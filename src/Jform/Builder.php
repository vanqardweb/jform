<?php
namespace Vanqard\Jform;

class Builder
{
    const REQUIRED_SCHEMA_ELEMENTS = [
        'schema',
        'meta',
        'fields'
    ];

    const REQUIRE_ELEMENT_MISSING_MSG = "Provided spec is missing the required '%s' element";
    const FIELDS_ELEMENT_EMPTY = "Whoever heard of a form with no fields?";

    private $specArray = [];

    public function build($jsonSpec)
    {
        if (is_string($jsonSpec)) {
            $jsonSpec = $this->parseJsonSpec($jsonSpec);
        } else if (is_array($jsonSpec)) {
            $this->validateSpecArray($jsonSpec);
        }

        $this->specArray = $jsonSpec;

        $form = $this->createFormInstance($this->specArray['schema']);

        $this->buildMeta($form)
            ->buildFields($form);





        return $form;
    }

    public function buildMeta(Form $form)
    {
        if (!empty($this->specArray['meta'])) {
            foreach ($this->specArray['meta']  as $metaKey => $metaValue) {
                $form->addMeta($metaKey, $metaValue);
            }
        }

        return $this;
    }

    /**
     * @param Form $form
     * @return Builder $builder - fluent interface
     */
    public function buildFields(Form $form)
    {
        $mask = $form->getMeta('mask');

        if (!is_null($mask)) {
            $this->setFieldMask($form, $mask);
        }

        if (!empty($this->specArray['fields'])) {
            foreach($this->specArray['fields'] as $fieldSpec) {
                $field = $this->buildField($fieldSpec);
                $form->addField($field);
            }
        }

        return $this;
    }

    /**
     * @param $mask
     * @return array|string
     */
    private function setFieldMask(Form $form, $mask)
    {
        if (is_string($mask) && strpos($mask, ',')) {
            $mask = array_map(function ($elem) {
                return trim($elem);
            }, explode(',', $mask));
        }
        return $this->fieldMask = $mask;
    }

    public function buildField(array $fieldSpec)
    {
        $field = new Field($fieldSpec['name']);
        $field->setValue($fieldSpec['value']);

        if (!empty($fieldSpec['attributes'])) {
            foreach ($fieldSpec['attributes'] as $attrKey => $attrvalue) {
                $field->addAttribute($attrKey, $attrvalue);

            }
        }

        return $field;
    }

    public function parseJsonSpec($jsonSpec = '')
    {
        $specArray = json_decode($jsonSpec, true);

        if (!$this->validateSpecArray($specArray)) {
            throw new FormException("Cannot generate valid spec array from provided spec parameter");
        }

        return $specArray;
    }

    protected function validateSpecArray(array $specArray)
    {
        foreach (self::REQUIRED_SCHEMA_ELEMENTS as $required) {
            if (!array_key_exists($required, $specArray)) {
                $this->throwException(
                    self::REQUIRE_ELEMENT_MISSING_MSG,
                    $required
                );
            }
        }

        // Test any fields for require name and value properties
        if (empty($specArray['fields'])) {
            $this->throwException(self::FIELDS_ELEMENT_EMPTY, null);
        }

        return true;
    }

    protected function throwException($msgTemplate, $replacementString = null)
    {
        $msg = !is_null($replacementString) ?: sprintf($msgTemplate, $replacementString);
        throw new FormException($msg);
    }

    protected function createFormInstance($schemaName)
    {
        $form = new Form($schemaName);
        return $form;
    }
}
