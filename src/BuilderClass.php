<?php

namespace BigShark\SQLToBuilder;

use BigShark\SQLToBuilder\Converter\Factory;
use PHPSQLParser\PHPSQLParser;

class BuilderClass
{
    /**
     * @var string
     */
    protected $sql;

    /**
     * @var PHPSQLParser
     */
    protected $sqlParser;

    /**
     * @var Factory
     */
    protected $converterFactory;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @param string $sql
     */
    public function __construct($sql)
    {
        $this->sql = $sql;
        $this->sqlParser = new PHPSQLParser();
        $this->converterFactory = new Factory();
        $this->generator = new Generator('DB');
    }

    /**
     * @return string
     */
    public function convert()
    {
        $parsed = $this->sqlParser->parse($this->sql);

        foreach ($parsed as $section => $data) {
            if ($this->converterFactory->canCreate($section)) {
                $converter = $this->converterFactory->create($section);
                $result = $converter->convert($data);
                foreach ($result as $function) {
                    $this->generator->addFunction($function['name'], (isset($function['args']) ? $function['args'] : []));
                }
            }
        }

        $this->generator->addFunction('get');

        return $this->generator->generate();
    }
}
