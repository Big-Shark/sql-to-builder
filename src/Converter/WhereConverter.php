<?php

namespace BigShark\SQLToBuilder\Converter;


class WhereConverter implements ConverterInterface
{
    public function convert($where)
    {
        $i = 0;
        $w = [];
        foreach($where as $key=>$item)
        {
            if('colref' === $item['expr_type'])
            {
                $value = $item['base_expr'];
                if( isset($item['no_quotes']['parts'][0]) )
                {
                    $value = $item['no_quotes']['parts'][0];
                }
                $w[$i]['args']['col'] = $value;
            }
            elseif('const' === $item['expr_type'])
            {
                $w[$i]['args']['value'] = $item['base_expr'];
            }
            elseif('operator' === $item['expr_type'] AND 'or' !== $item['base_expr'] AND 'and' !== $item['base_expr'] )
            {
                $w[$i]['args']['operator'] = $item['base_expr'];
            }
            elseif('operator' === $item['expr_type'] AND ( 'or' === $item['base_expr'] OR 'and' === $item['base_expr'] ))
            {
                $i++;
                $w[$i]['connector'] = $item['base_expr'];
            }
            //dump($w);
        }

        if( $w )
        {
            $r = [];
            foreach($w as $where)
            {
                //dump($where);
                if( ! isset($where['connector']))
                {
                    $where['connector'] = 'and';
                }
                if( ! is_numeric($where['args']['value'])  )
                {
                    $where['args']['value'] = "'".$where['args']['value']."'";
                }
                $r[] =  $where['connector'] . "Where('" . $where['args']['col'] . "', '" . $where['args']['operator'] . "', " . $where['args']['value'] . ")";
            }
            return $r;
        }
        throw new \Exception('Not valid where');
    }
}