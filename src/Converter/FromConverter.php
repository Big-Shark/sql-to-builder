<?php

namespace BigShark\SQLToBuilder\Converter;


class FromConverter implements ConverterInterface
{
    public function convert($from)
    {
        if (count($from) == 1)
        {
            if('table' === $from[0]['expr_type'])
            {
                $value = $from[0]['table'];
                if( isset($from[0]['no_quotes']['parts'][0]) )
                {
                    $value = $from[0]['no_quotes']['parts'][0];
                }
                return "table('".$value."')";
            }
        }

        throw new \Exception('Not valid from');
    }
}