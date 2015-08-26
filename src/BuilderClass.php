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
     * @param string $sql
     */
    public function __construct($sql)
    {
        $this->sql = $sql;
        $this->sqlParser = new PHPSQLParser();
        $this->converterFactory = new Factory();
    }

    /**
     * @return string
     */
    public function convert()
    {
        $parsed = $this->sqlParser->parse($this->sql);

        $builderParts = [];

        foreach ($parsed as $section => $data) {
            if ($this->converterFactory->canCreate($section)) {
                $converter = $this->converterFactory->create($section);
                $builderParts[$section] = $converter->convert($data);
            }
        }

        return $this->buildFromParts($builderParts);
    }

    /**
     * @param array $builderParts
     * @param bool  $main
     *
     * @return string
     */
    protected function buildFromParts($builderParts, $main = true)
    {
        $builderParts = array_filter($builderParts);

        if ($main) {
            $from = $builderParts['FROM'];
            unset($builderParts['FROM']);
            array_unshift($builderParts, $from);
            array_push($builderParts, 'get()');

            return 'DB::'.$this->buildFromParts($builderParts, false);
        } else {
            foreach ($builderParts as $key => $part) {
                if (is_array($part)) {
                    $builderParts[$key] = $this->buildFromParts($part, false);
                }
            }

            return implode($builderParts, '->');
        }
    }
}
