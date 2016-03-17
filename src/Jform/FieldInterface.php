<?php
namespace Vanqard\Jform;

interface FieldInterface
{
    public function getName();

    public function setValue($value);

    public function getValue();

    public function jsonSerialize();
}