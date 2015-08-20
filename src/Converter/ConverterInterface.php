<?php

namespace BigShark\SQLToBuilder\Converter;


interface ConverterInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function convert($data);
}