<?php

namespace BigShark\SQLToBuilder\Converter;


class SelectConverter implements ConverterInterface
{
    public function convert($select)
    {
        if (count($select) == 1 )
        {
            $value = $select[0]['base_expr'];
            if( isset($select[0]['no_quotes']['parts'][0]) )
            {
                $value = $select[0]['no_quotes']['parts'][0];
            }
            if( '*' === $value)
            {
                return null;
            }
            unset($value);
        }

        $s = [];
        foreach($select as $item)
        {
            if( 'colref' === $item['expr_type'])
            {
                $value = $item['base_expr'];
                if( isset($item['no_quotes']['parts'][0]) )
                {
                    $value = $item['no_quotes']['parts'][0];
                }

                $s[] = $value;
            }
        }
        if( is_array($s) and count($s) )
        {
            return "select('".implode($s, "', '")."')";
        }
        throw new \Exception('Not valid select');
    }
}