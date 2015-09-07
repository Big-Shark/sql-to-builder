<?php

namespace BigShark\SQLToBuilder\Converter;

class WhereConverter extends Converter implements ConverterInterface
{
    public function convert($where)
    {
        $i = 0;
        $w = [];
        foreach ($where as $key => $item) {
            if ('colref' === $item['expr_type']) {
                $w[$i]['col'] = $this->getValueWithoutQuotes($item);
            } elseif ('const' === $item['expr_type']) {
                $w[$i]['value'] = $this->getValueWithoutInvertedCommas($item['base_expr']);
            } elseif ('in-list' === $item['expr_type']) {
                $callback = [$this, 'getValueWithoutInvertedCommas'];
                $w[$i]['value'] = array_map($callback, array_column($item['sub_tree'], 'base_expr'));
            } elseif ('operator' === $item['expr_type']) {
                $upper = strtoupper($item['base_expr']);
                if ('NOT' === $upper) {
                    $w[$i]['not'] = true;
                } elseif ('OR' !== $upper && 'AND' !== $upper) {
                    $w[$i]['operator'] = $item['base_expr'];
                } elseif ('OR' === $upper || 'AND' === $upper) {
                    $i++;
                    $w[$i]['connector'] = $upper;
                }
            }
        }

        $result = [];
        if (is_array($w) && count($w) > 0) {
            foreach ($w as $where) {
                if (isset($where['connector']) && 'OR' === $where['connector']) {
                    $where['where'] = 'orWhere';
                } else {
                    $where['where'] = 'where';
                }

                if (isset($where['not'])) {
                    $where['not'] = 'Not';
                } else {
                    $where['not'] = '';
                }

                if ('IN' === strtoupper($where['operator'])) {
                    $result[] = $this->format($where['where'].$where['not'].'In', [$where['col'], $where['value']]);
                } elseif ('IS' === strtoupper($where['operator']) && 'NULL' === strtoupper($where['value'])) {
                    $result[] = $this->format($where['where'].$where['not'].'Null', [$where['col']]);
                } else {
                    $result[] = $this->format($where['where'], [$where['col'], $where['operator'], $where['value']]);
                }
            }
        }

        return $result;
    }
}
