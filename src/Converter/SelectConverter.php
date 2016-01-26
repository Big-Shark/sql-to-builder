<?php

namespace BigShark\SQLToBuilder\Converter;

use BigShark\SQLToBuilder\Generator;

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
                    $value .= ' AS '.$this->getValueWithoutQuotes($item['alias'], 'name');
                }
                $s[] = $value;
            } elseif ('aggregate_function' === $item['expr_type'] || 'function' === $item['expr_type']) {
                $function = strtoupper($item['base_expr']);
                $value = $function.'('.implode(', ', array_column($item['sub_tree'], 'base_expr')).')';
                if (isset($item['alias']) && is_array($item['alias'])) {
                    $value .= ' AS '.$item['alias']['name'];
                }

                $generator = new Generator('DB');
                $generator->addFunction('raw', [$value]);

                $s[] = $generator;
            }
        }
        if (is_array($s) && count($s)) {
            return [$this->format('select', $s)];
        }
        throw new \Exception('Not valid select');
    }
}
