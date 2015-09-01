<?php

namespace BigShark\SQLToBuilder\Converter;

interface ConverterInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function convert($data);
}
