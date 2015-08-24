<?php

namespace BigShark\SQLToBuilder\Converter;

class SelectConverter extends Converter implements ConverterInterface
{
    public function convert($select)
    {
        if (count($select) == 1) {
            $value = $this->getValueWithoutQuotes($select[0]);
            if ('*' === $value) {
                return;
            }
            unset($value);
        }

        $s = [];
        foreach ($select as $item) {
            if ('colref' === $item['expr_type']) {
                $value = $this->getValueWithoutQuotes($item);
                $s[] = $value;
            }
        }
        if (is_array($s) && count($s)) {
            return "select('".implode($s, "', '")."')";
        }
        throw new \Exception('Not valid select');
    }
}
