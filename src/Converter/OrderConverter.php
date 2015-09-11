<?php

namespace BigShark\SQLToBuilder\Converter;

class OrderConverter extends Converter implements ConverterInterface
{
    public function convert($order)
    {
        $result = [];
        foreach ($order as $item) {
            $args = [];
            $args[] = $this->getValueWithoutQuotes($item, 'base_expr');
            if ('ASC' !== strtoupper($item['direction'])) {
                $args[] = strtoupper($item['direction']);
            }
            $result[] = $this->format('orderBy', $args);
        }

        return $result;
    }
}
