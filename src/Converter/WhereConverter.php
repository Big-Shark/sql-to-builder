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
                $w[$i]['value'] = $item['base_expr'];
            } elseif ('in-list' === $item['expr_type']) {
                $w[$i]['value'] = array_column($item['sub_tree'], 'base_expr');
            } elseif ('operator' === $item['expr_type']) {
                if ('NOT' === $item['base_expr']) {
                    $w[$i]['not'] = true;
                } elseif ('or' !== $item['base_expr'] && 'and' !== $item['base_expr']) {
                    $w[$i]['operator'] = $item['base_expr'];
                } elseif ('or' === $item['base_expr'] || 'and' === $item['base_expr']) {
                    $i++;
                    $w[$i]['connector'] = $item['base_expr'];
                }
            }
        }

        if (is_array($w) && count($w) > 0) {
            $r = [];
            foreach ($w as $where) {
                if (isset($where['connector']) && 'or' === $where['connector']) {
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
                    $r[] = $where['where'].$where['not'].'In(\''.$where['col'].'\', ['.implode(', ', $where['value']).']'.')';
                } else {
                    $r[] = $where['where'].'(\''.$where['col'].'\', \''.$where['operator'].'\', '.$where['value'].')';
                }
            }

            return $r;
        }

        return [];
    }
}
