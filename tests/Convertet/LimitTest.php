<?php

namespace BigShark\SQLToBuilder\Test\Converter;

class LimitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|\BigShark\SQLToBuilder\Converter\LimitConverter
     */
    protected $converter = null;

    public function setUp()
    {
        $this->converter = new \BigShark\SQLToBuilder\Converter\LimitConverter();
    }

    public function testEmpty()
    {
        $limit = [];
        $result = $this->converter->convert($limit);

        $this->assertEquals($result, []);
    }

    public function testSkip()
    {
        $limit = ['offset' => 5];
        $result = $this->converter->convert($limit);

        $this->assertEquals($result, [['name' => 'skip', 'args' => [5]]]);
    }

    public function testTake()
    {
        $limit = ['rowcount' => 5];
        $result = $this->converter->convert($limit);

        $this->assertEquals($result, [['name' => 'take', 'args' => [5]]]);
    }

    public function testAll()
    {
        $limit = ['offset' => 5, 'rowcount' => 5];
        $result = $this->converter->convert($limit);

        $this->assertEquals($result, [['name' => 'skip', 'args' => [5]], ['name' => 'take', 'args' => [5]]]);
    }
}
