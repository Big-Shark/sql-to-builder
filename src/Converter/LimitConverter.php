<?php

namespace BigShark\SQLToBuilder\Converter;

class LimitConverter extends Converter implements ConverterInterface
{
    public function convert($limit)
    {
        $result = [];
        if (isset($limit['offset']) && (int) $limit['offset'] > 0) {
            $result[] = $this->format('skip', [$limit['offset']]);
        }

        if (isset($limit['rowcount']) && (int) $limit['rowcount'] > 0) {
            $result[] = $this->format('take', [$limit['rowcount']]);
        }

        return $result;
    }
}
