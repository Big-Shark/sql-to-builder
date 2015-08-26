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
                $w[$i]['args']['col'] = $this->getValueWithoutQuotes($item);
            } elseif ('const' === $item['expr_type']) {
                $w[$i]['args']['value'] = $item['base_expr'];
            } elseif ('in-list' === $item['expr_type']) {
                $w[$i]['args']['value'] = array_column($item['sub_tree'], 'base_expr');
            } elseif ('operator' === $item['expr_type'] && 'or' !== $item['base_expr'] && 'and' !== $item['base_expr'] ) {
                $w[$i]['args']['operator'] = $item['base_expr'];
            } elseif ('operator' === $item['expr_type'] || ('or' === $item['base_expr'] || 'and' === $item['base_expr'])) {
                $i++;
                $w[$i]['connector'] = $item['base_expr'];
            }
        }

        if (is_array($w) && count($w) > 0) {
            $r = [];
            foreach ($w as $where)
            {
                if (isset($where['connector']) && 'or' === $where['connector']) {
                    $where['where'] = 'orWhere';
                } else {
                    $where['where'] = 'where';
                }

                if ('IN' === $where['args']['operator'])
                {
                    $r[] =  $where['where'] . "In('" . $where['args']['col'] . "', [" . implode(', ', $where['args']['value']).']' . ")";
                } else {
                    if( ! is_numeric($where['args']['value'])  )
                    {
                        $where['args']['value'] = "'".$where['args']['value']."'";
                    }
                    $r[] =  $where['where'] . "('" . $where['args']['col'] . "', '" . $where['args']['operator'] . "', " . $where['args']['value'] . ")";
                }
            }

            return $r;
        }

        return [];
    }
}

