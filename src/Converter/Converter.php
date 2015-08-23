<?php

namespace BigShark\SQLToBuilder\Converter;


abstract class Converter
{
    /**
     * Get item value without quotes
     *
     * @param array $item Item
     * @param string $key Value key
     * @return mixed
     */
    protected function getValueWithoutQuotes($item, $key = 'base_expr')
    {
        $value = $item[$key];
        if( isset($item['no_quotes']['parts'][0]) )
        {
            $value = $item['no_quotes']['parts'][0];
        }
        return $value;
    }
}