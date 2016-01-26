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
     * @throws \Exception
     */
    public function convert()
    {
        $parsed = $this->sqlParser->parse($this->sql);

        if (false === $parsed) {
            throw new \Exception('SQL query is not valid');
        }

        $results = [];
        foreach ($parsed as $section => $data) {
            if ($this->converterFactory->canCreate($section)) {
                $converter = $this->converterFactory->create($section);
                $results = array_merge($results, $converter->convert($data));
            }
        }

        usort($results, function($a, $b) {
            return (int) ('table' === $b['name']);
        });

        foreach ($results as $function) {
            $args = isset($function['args']) ? $function['args'] : [];
            $this->generator->addFunction($function['name'], $args);
        }

        $this->generator->addFunction('get');

        return $this->generator->generate();
    }
}
