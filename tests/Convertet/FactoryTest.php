<?php

namespace BigShark\SQLToBuilder\Test\Converter;

use BigShark\SQLToBuilder\Converter\Factory;
use BigShark\SQLToBuilder\Converter\FromConverter;
use BigShark\SQLToBuilder\Converter\SelectConverter;
use BigShark\SQLToBuilder\Converter\WhereConverter;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testCanCreate()
    {
        $factory = new Factory();
        $this->assertTrue($factory->canCreate('from'));
        $this->assertTrue($factory->canCreate('select'));
        $this->assertTrue($factory->canCreate('where'));
        $this->assertFalse($factory->canCreate('foo'));
    }

    public function testCreate()
    {
        $factory = new Factory();
        $this->assertInstanceOf(FromConverter::class, $factory->create('from'));
        $this->assertInstanceOf(SelectConverter::class, $factory->create('select'));
        $this->assertInstanceOf(WhereConverter::class, $factory->create('where'));

        try {
            $factory->create('foo');
            $this->assertTrue(false);
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }
}
