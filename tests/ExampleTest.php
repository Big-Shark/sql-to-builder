<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\BuilderClass;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
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
        $this->assertEquals($result, "DB::select('a', 'b', 'c')->table('table')->get()");
    }

    public function testSimpleSelectQuotes()
    {
        $result = (new BuilderClass('SELECT `a`, `b`, `c`  FROM table'))->convert();
        $this->assertEquals($result, "DB::select('a', 'b', 'c')->table('table')->get()");
    }

    public function testWhereQuotes()
    {
        $result = (new BuilderClass('SELECT *  FROM table WHERE `a` = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->where('a', '=', 1)->get()");

        $result = (new BuilderClass('SELECT *  FROM table WHERE a = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->where('a', '=', 1)->get()");
    }

    public function testWhere()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` = 1 and `b` = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->where('a', '=', 1)->where('b', '=', 1)->get()");

        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` = 1 or `b` = 1'))->convert();
        $this->assertEquals($result, "DB::table('table')->where('a', '=', 1)->orWhere('b', '=', 1)->get()");
    }

    public function testWhereNumericAndText()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` = 1 and `b` = \'b\''))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->where(\'a\', \'=\', 1)->where(\'b\', \'=\', \'b\')->get()');
    }

    public function testWhereIn()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` IN (\'a\', \'b\') or `b` IN (\'c\', \'d\')'))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->whereIn(\'a\', [\'a\', \'b\'])->orWhereIn(\'b\', [\'c\', \'d\'])->get()');
    }

    public function testWhereNotIn()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` NOT IN (\'a\', \'b\') or `b` NOT IN (\'c\', \'d\')'))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->whereNotIn(\'a\', [\'a\', \'b\'])->orWhereNotIn(\'b\', [\'c\', \'d\'])->get()');
    }

    public function testWhereLike()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` LIKE \'%a%\''))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->where(\'a\', \'LIKE\', \'%a%\')->get()');
    }

    public function testWhereIsNull()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` IS NULL and `b` IS NULL'))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->whereNull(\'a\')->whereNull(\'b\')->get()');

        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` IS NULL or `b` IS NULL'))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->whereNull(\'a\')->orWhereNull(\'b\')->get()');
    }

    public function testWhereIsNotNull()
    {
        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` IS NOT NULL and `b` IS NOT NULL'))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->whereNotNull(\'a\')->whereNotNull(\'b\')->get()');

        $result = (new BuilderClass('SELECT *  FROM `table` WHERE `a` IS NOT NULL or `b` IS NOT NULL'))->convert();
        $this->assertEquals($result, 'DB::table(\'table\')->whereNotNull(\'a\')->orWhereNotNull(\'b\')->get()');
    }

    public function testSelectAlias()
    {
        $result = (new BuilderClass('SELECT `a` as `b`  FROM `table`'))->convert();
        $this->assertEquals($result, 'DB::select(\'a as b\')->table(\'table\')->get()');

        $result = (new BuilderClass('SELECT a as b  FROM `table`'))->convert();
        $this->assertEquals($result, 'DB::select(\'a as b\')->table(\'table\')->get()');
    }
}
