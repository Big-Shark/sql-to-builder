<?php

namespace BigShark\SQLToBuilder\Converter;

class GroupConverter extends Converter implements ConverterInterface
{
    public function convert($group)
    {
        $result = [];
        foreach ($group as $item) {
            $args = [];
            $args[] = $this->getValueWithoutQuotes($item, 'base_expr');
            $result[] = $this->format('groupBy', $args);
        }

        return $result;
    }
}
