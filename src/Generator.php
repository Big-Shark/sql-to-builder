<?php

namespace BigShark\SQLToBuilder;

/**
 * Class Generator
 * @package BigShark\SQLToBuilder
 */
class Generator {

    /**
     * @var array
     */
    protected $functions = [];
    /**
     * @var null
     */
    protected $class = null;

    /**
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param $name
     * @param array $args
     * @return $this
     */
    public function addFunction($name, $args = [])
    {
        $this->functions[] = ['name' => $name, 'args' => $args];
        return $this;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * @return bool
     */
    public function isStatic()
    {
        return !(substr($this->class, 0, 1) === '$');
    }

    /**
     * @return string
     */
    public function generate()
    {
        if (count($this->functions) === 0) {
            throw new \Exception('function list empty');
        }

        $parts = [];
        foreach ($this->functions as $function) {
            $args = [];
            if ($function['args']) {
                $args = $this->generateArgs($function['args']);
            }
            $parts[] = $function['name'].'('.implode(', ', $args).')';
        }

        $result = $this->class.($this->isStatic() ? '::' : '->').implode($parts, '->');
        return $result;
    }

    /**
     * @param $_args
     * @return array
     */
    protected function generateArgs($_args)
    {
        $args = [];
        foreach ($_args as $arg) {
            if (is_int($arg) || is_float($arg) || is_numeric($arg)) {
                $args[] = $arg;
            } elseif(is_array($arg)) {
                $args[] = '['.implode(', ', $this->generateArgs($arg)).']';
            } else {
                $args[] = '\'' . $arg . '\'';
            }
        }
        return $args;
    }
}