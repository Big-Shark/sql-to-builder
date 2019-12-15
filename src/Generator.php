<?php

namespace BigShark\SQLToBuilder;

class Generator
{
    /**
     * @var array
     */
    protected $functions = [];

    /**
     * @var array
     */
    protected $class = [];

    /**
     * @param string $class
     * @param array  $args
     */
    public function __construct($class, $args = [])
    {
        $this->class = ['name' => $class, 'args' => $args];
    }

    /**
     * @param string $name
     * @param array  $args
     *
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
        return !(substr($this->class['name'], 0, 1) === '$');
    }

    /**
     * @throws \Exception
     *
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

        $class = $this->class['name'];
        $class .= count($this->class['args']) ? '('.implode(', ', $this->class['args']).')' : '';

        $result = $class.($this->isStatic() ? '::' : '->').implode('->', $parts);

        return $result;
    }

    /**
     * @param $_args
     *
     * @return array
     */
    protected function generateArgs($_args)
    {
        $args = [];
        foreach ($_args as $arg) {
            if (is_int($arg) || is_float($arg) || is_numeric($arg)) {
                $args[] = $arg;
            } elseif (is_array($arg)) {
                $args[] = '['.implode(', ', $this->generateArgs($arg)).']';
            } elseif ($arg instanceof static) {
                $args[] = $arg->generate();
            } else {
                $args[] = '\''.$arg.'\'';
            }
        }

        return $args;
    }
}
