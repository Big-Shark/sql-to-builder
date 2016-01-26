<?php

namespace BigShark\SQLToBuilder\Converter;

class FromConverter extends Converter implements ConverterInterface
{
    public function convert($from)
    {
        $result = [];
        if ('table' === $from[0]['expr_type']) {
            $value = $this->getValueWithoutQuotes($from[0], 'table');
            if (isset($from[0]['alias']) && is_array($from[0]['alias'])) {
                $value .= ' AS '.$this->getValueWithoutQuotes($from[0]['alias'], 'name');
            }
            $result[] = $this->format('table', [$value]);
        }
        unset($from[0]);
        foreach ($from as $item) {
            if (in_array($item['join_type'], ['LEFT', 'RIGHT'], true)) {
                $table = $this->getValueWithoutQuotes($item, 'table');
                if (isset($item['alias']) && is_array($item['alias'])) {
                    $table .= ' AS '.$this->getValueWithoutQuotes($item['alias'], 'name');
                }
                if ('ON' === strtoupper($item['ref_type'])) {
                    $args = [
                        $table,
                        $this->getValueWithoutQuotes($item['ref_clause'][0], 'base_expr'),
                        $item['ref_clause'][1]['base_expr'],
                        $this->getValueWithoutQuotes($item['ref_clause'][2], 'base_expr'),
                    ];
                    $result[] = $this->format(strtolower($item['join_type']).'Join', $args);
                }
            }
        }

        return $result;
    }
}
