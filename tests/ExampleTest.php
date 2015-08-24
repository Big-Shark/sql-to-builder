<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\BuilderClass;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    protected $sql = 'SELECT a, b, c  FROM some_table WHERE d > 5';

    /**
     * Test that true does in fact equal true.
     */
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testSimpleQuery()
    {
        $result = (new BuilderClass('SELECT * FROM table'))->convert();
        $this->assertEquals($result, "DB::table('table')->get()");
    }

    public function testFromQuotes()
    {
        $result = (new BuilderClass('SELECT * FROM `table`'))->convert();
        $this->assertEquals($result, "DB::table('table')->get()");
    }

    public function testSelectQuotes()
    {
        $result = (new BuilderClass('SELECT `*` FROM table'))->convert();
        $this->assertEquals($result, "DB::table('table')->get()");
    }

    public function testSimpleSelect()
    {
        $result = (new BuilderClass('SELECT a, b, c  FROM table'))->convert();
        $this->assertEquals($result, "DB::table('table')->select('a', 'b', 'c')->get()");
    }

    public function testSimpleSelectQuotes()
    {
        $result = (new BuilderClass('SELECT `a`, `b`, `c`  FROM table'))->convert();
        $this->assertEquals($result, "DB::table('table')->select('a', 'b', 'c')->get()");
    }

    public function testWhereQuotes()
    {
        $result = (new BuilderClass('SELECT *  FROM table WHERE `a` = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->andWhere('a', '=', 1)->get()");

        $result = (new BuilderClass('SELECT *  FROM table WHERE a = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->andWhere('a', '=', 1)->get()");
    }

    public function testWhere()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` = 1 and `b` = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->andWhere('a', '=', 1)->andWhere('b', '=', 1)->get()");

        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` = 1 or `b` = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->andWhere('a', '=', 1)->orWhere('b', '=', 1)->get()");
    }
}
