<?php

namespace BigShark\SQLToBuilder\Converter;

abstract class Converter
{
    /**
     * Get item value without quotes.
     *
     * @param array  $item Item
     * @param string $key  Value key
     *
     * @return mixed
     */
    protected function getValueWithoutQuotes($item, $key = 'base_expr')
    {
        $value = $item[$key];

        if (isset($item['no_quotes']['parts'][0])) {
            if (isset($item['no_quotes']['delim'])) {
                $value = implode($item['no_quotes']['delim'], $item['no_quotes']['parts']);
            } else {
                $value = $item['no_quotes']['parts'][0];
            }
        }

        return $value;
    }

    /**
     * Get item value without quotes.
     *
     * @param string $value Value
     *
     * @return mixed
     */
    protected function getValueWithoutInvertedCommas($value)
    {
        if (substr($value, 0, 1) === '\'' && substr($value, -1, 1) === '\'') {
            $value = substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * @param $name
     * @param $args
     *
     * @return array
     */
    protected function format($name, array $args = null)
    {
        $result = ['name' => $name];
        if ($args !== null) {
            $result['args'] = $args;
        }

        return $result;
    }
}
