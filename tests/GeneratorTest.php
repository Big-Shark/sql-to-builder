<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\Generator;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testEmpty()
    {
        $generator = new Generator('$db');
        try {
            $generator->generate();
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'function list empty');
        }
    }

    public function testAddFunctionAndGetFunctions()
    {
        $generator = new Generator('$db');
        $generator->addFunction('test');

        $this->assertEquals($generator->getFunctions(), [['name' => 'test', 'args' => []]]);
    }

    public function testConstructorAndGetClass()
    {
        $generator = new Generator('$db');
        $this->assertEquals($generator->getClass(), ['name' => '$db', 'args' => []]);

        $generator = new Generator('$db', [1]);
        $this->assertEquals($generator->getClass(), ['name' => '$db', 'args' => [1]]);

        $generator = new Generator('DB', [1]);
        $this->assertEquals($generator->getClass(), ['name' => 'DB', 'args' => [1]]);
    }

    public function testIsStatic()
    {
        $generator = new Generator('DB');
        $this->assertTrue($generator->isStatic());

        $generator = new Generator('$db');
        $this->assertFalse($generator->isStatic());
    }

    public function testGenerator()
    {
        $generator = new Generator('DB');
        $generator->addFunction('test');
        $this->assertEquals($generator->generate(), 'DB::test()');

        $generator = new Generator('$db');
        $generator->addFunction('test');
        $this->assertEquals($generator->generate(), '$db->test()');
    }

    public function testAddFunction()
    {
        $generator = new Generator('DB');
        $generator->addFunction('test');
        $generator->addFunction('test');
        $this->assertEquals($generator->generate(), 'DB::test()->test()');

        $generator = new Generator('$db');
        $generator->addFunction('test');
        $generator->addFunction('test');
        $this->assertEquals($generator->generate(), '$db->test()->test()');
    }

    public function testAgruments()
    {
        $generator = new Generator('DB');
        $generator->addFunction('test', [1]);
        $this->assertEquals($generator->generate(), 'DB::test(1)');

        $generator = new Generator('DB');
        $generator->addFunction('test', ['a']);
        $this->assertEquals($generator->generate(), 'DB::test(\'a\')');

        $generator = new Generator('DB');
        $generator->addFunction('test', [1, 2, 3]);
        $this->assertEquals($generator->generate(), 'DB::test(1, 2, 3)');

        $generator = new Generator('DB');
        $generator->addFunction('test', ['a', 'b', 'c']);
        $this->assertEquals($generator->generate(), 'DB::test(\'a\', \'b\', \'c\')');

        $generator = new Generator('DB');
        $generator->addFunction('test', [[1, 2, 3]]);
        $this->assertEquals($generator->generate(), 'DB::test([1, 2, 3])');

        $generator = new Generator('DB');
        $generator->addFunction('test', [['a', 'b', 'c']]);
        $this->assertEquals($generator->generate(), 'DB::test([\'a\', \'b\', \'c\'])');
    }
}
