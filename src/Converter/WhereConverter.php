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
            } elseif ('operator' === $item['expr_type'] && 'or' !== $item['base_expr'] && 'and' !== $item['base_expr']) {
                $w[$i]['args']['operator'] = $item['base_expr'];
            } elseif ('operator' === $item['expr_type'] || ('or' === $item['base_expr'] || 'and' === $item['base_expr'])) {
                $i++;
                $w[$i]['connector'] = $item['base_expr'];
            }
        }

        if (is_array($w) && count($w) > 0) {
            $r = [];
            foreach ($w as $where) {
                if (!isset($where['connector'])) {
                    $where['connector'] = 'and';
                }
                if (!is_numeric($where['args']['value'])) {
                    $where['args']['value'] = "'".$where['args']['value']."'";
                }
                $r[] = $where['connector']."Where('".$where['args']['col']."', '".$where['args']['operator']."', ".$where['args']['value'].')';
            }

            return $r;
        }

        return [];
    }
}
