<?php

namespace BigShark\SQLToBuilder\Converter;

use BigShark\SQLToBuilder\Generator;

interface ConverterInterface
{
   /**
     * @param array $data
     *
     * @return array
     */
    public function convert($data);
}
