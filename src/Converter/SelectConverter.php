<?php

namespace BigShark\SQLToBuilder\Converter;

class SelectConverter extends Converter implements ConverterInterface
{
    public function convert($select)
    {
        if (count($select) == 1) {
            $value = $this->getValueWithoutQuotes($select[0]);
            if ('*' === $value) {
                return [];
            }
            unset($value);
        }

        $s = [];
        foreach ($select as $item) {
            if ('colref' === $item['expr_type']) {
                $value = $this->getValueWithoutQuotes($item);
                if (isset($item['alias']) && is_array($item['alias'])) {
                    $value .= ' as '.$this->getValueWithoutQuotes($item['alias'], 'name');
                }
                $s[] = $value;
            }
        }
        if (is_array($s) && count($s)) {
            return [$this->format('select', $s)];
        }
        throw new \Exception('Not valid select');
    }
}
