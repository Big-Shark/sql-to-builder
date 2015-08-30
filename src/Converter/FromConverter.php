<?php

namespace BigShark\SQLToBuilder\Converter;

class FromConverter extends Converter implements ConverterInterface
{
    public function convert($from)
    {
        if (count($from) == 1) {
            if ('table' === $from[0]['expr_type']) {
                $value = $this->getValueWithoutQuotes($from[0], 'table');
                return [$this->format('table', [$value])];
            }
        }
        throw new \Exception('Not valid from');
    }
}
