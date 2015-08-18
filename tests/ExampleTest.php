<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\BuilderClass;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    protected $sql = 'SELECT a, b, c  FROM some_table WHERE d > 5';


    /**
     * Test that true does in fact equal true
     */
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testConvert()
    {
        $builder = new BuilderClass($this->sql);
        $result = $builder->convert();
        $this->assertEquals($result, "DB::table('some_table')->select('a', 'b', 'c')->where('d', '>', 5)->get()");
    }

    public function testFrom()
    {
        $method = new \ReflectionMethod(
            'BigShark\SQLToBuilder\BuilderClass', 'parseFrom'
        );

        $method->setAccessible(TRUE);

        $obj = new BuilderClass($this->sql);
        $result = $method->invokeArgs($obj, [ [ ['expr_type' => 'table', 'table' => 'some_table'] ] ]);
        $this->assertEquals("table('some_table')", $result);
    }
}
