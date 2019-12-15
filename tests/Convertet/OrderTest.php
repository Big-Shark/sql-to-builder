<?php

namespace BigShark\SQLToBuilder\Test\Converter;

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /**
     * @var null|\BigShark\SQLToBuilder\Converter\OrderConverter
     */
    protected $converter = null;

    public function setUp(): void
    {
        $this->converter = new \BigShark\SQLToBuilder\Converter\OrderConverter();
    }

    public function testEmpty()
    {
        $order = [];
        $result = $this->converter->convert($order);

        $this->assertEquals($result, []);
    }

    public function testAsc()
    {
        $order = [
            'expr_type' => 'colref',
            'base_expr' => 'id',
            'direction' => 'ASC',
        ];

        $result = $this->converter->convert([$order]);

        $this->assertEquals($result, [['name' => 'orderBy', 'args' => ['id']]]);
    }

    public function testDesk()
    {
        $order = [
            'expr_type' => 'colref',
            'base_expr' => 'id',
            'direction' => 'DESC',
        ];

        $result = $this->converter->convert([$order]);

        $this->assertEquals($result, [['name' => 'orderBy', 'args' => ['id', 'DESC']]]);
    }
}
