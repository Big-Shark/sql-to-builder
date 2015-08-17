<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\BuilderClass;

class ExampleTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Test that true does in fact equal true
     */
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testConvert()
    {
        $sql = 'SELECT a, b, c  FROM some_table WHERE d > 5';
        $builder = new BuilderClass($sql);
        $result = $builder->convert();
        $this->assertEquals($result, "DB::table('some_table')->select('a', 'b', 'c')->where('d', '>', 5)->get()");
    }
}
