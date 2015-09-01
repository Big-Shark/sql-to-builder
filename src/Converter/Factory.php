<?php

namespace BigShark\SQLToBuilder\Converter;

class Factory
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function canCreate($key)
    {
        return class_exists($this->getFullPath($key));
    }

    /**
     * @param string $key
     *
     * @return ConverterInterface
     */
    public function create($key)
    {
        if ($this->canCreate($key)) {
            $class = $this->getFullPath($key);

            return new $class();
        } else {
            throw new \InvalidArgumentException("{$key} converter not found");
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getFullPath($key)
    {
        $key = ucfirst(strtolower($key));

        return 'BigShark\\SQLToBuilder\\Converter\\'.$key.'Converter';
    }
}
