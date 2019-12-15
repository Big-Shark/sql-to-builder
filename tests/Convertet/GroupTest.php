<?php

namespace BigShark\SQLToBuilder\Test\Converter;

use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    /**
     * @var null|\BigShark\SQLToBuilder\Converter\GroupConverter
     */
    protected $converter = null;

    public function setUp(): void
    {
        $this->converter = new \BigShark\SQLToBuilder\Converter\GroupConverter();
    }

    public function testEmpty()
    {
        $order = [];
        $result = $this->converter->convert($order);

        $this->assertEquals($result, []);
    }

    public function testSimple()
    {
        $group = [
            'expr_type' => 'colref',
            'base_expr' => 'id',
            'no_quotes' => [
                'delim' => false,
                'parts' => ['id'],
            ],
            'sub_tree' => false,
        ];

        $result = $this->converter->convert([$group]);

        $this->assertEquals($result, [['name' => 'groupBy', 'args' => ['id']]]);

        $group['base_expr'] = 'id';

        $result = $this->converter->convert([$group]);

        $this->assertEquals($result, [['name' => 'groupBy', 'args' => ['id']]]);
    }
}
