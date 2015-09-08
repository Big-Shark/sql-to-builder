<?php

namespace BigShark\SQLToBuilder\Converter;

class FromConverter extends Converter implements ConverterInterface
{
    public function convert($from)
    {
        $result = [];
        if ('table' === $from[0]['expr_type']) {
            $value = $this->getValueWithoutQuotes($from[0], 'table');

            $result[] = $this->format('table', [$value]);
        }
        unset($from[0]);
        foreach ($from as $item) {
            if ('LEFT' === $item['join_type']) {
                $table = $this->getValueWithoutQuotes($item, 'table');
                if ('ON' === strtoupper($item['ref_type'])) {
                    $args = [
                        $table,
                        $this->getValueWithoutQuotes($item['ref_clause'][0], 'base_expr'),
                        $item['ref_clause'][1]['base_expr'],
                        $this->getValueWithoutQuotes($item['ref_clause'][2], 'base_expr'),
                    ];
                    $result[] = $this->format('join', $args);
                }
            }
        }

        return $result;
    }
}
