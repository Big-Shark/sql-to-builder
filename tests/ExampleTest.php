<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\BuilderClass;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function testNotCorrectSql()
    {
        try {
            (new BuilderClass('test'))->convert();
            $this->assertFalse();
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'SQL query is not valid');
        }
    }

    /**
     * @dataProvider examples
     */
    public function testExample($sql, $actual)
    {
        $result = (new BuilderClass($sql))->convert();
        $this->assertEquals($result, $actual);
    }

    public function examples()
    {
        return [
            [
                'SELECT * FROM table',
                'DB::table(\'table\')->get()',
            ],
            [
                'SELECT * FROM `table`',
                'DB::table(\'table\')->get()',
            ],
            [
                'SELECT `*` FROM table',
                'DB::table(\'table\')->get()',
            ],
            [
                'SELECT `*` FROM table t',
                'DB::table(\'table AS t\')->get()',
            ],
            [
                'SELECT a, b, c  FROM table',
                'DB::table(\'table\')->select(\'a\', \'b\', \'c\')->get()',
            ],
            [
                'SELECT `a`, `b`, `c`  FROM table',
                'DB::table(\'table\')->select(\'a\', \'b\', \'c\')->get()',
            ],
            [
                'SELECT *  FROM table WHERE `a` = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->get()',
            ],
            [
                'SELECT *  FROM table WHERE a = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` = 1 and `b` = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->where(\'b\', \'=\', 1)->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` = 1 or `b` = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->orWhere(\'b\', \'=\', 1)->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` = 1 and `b` = \'b\'',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->where(\'b\', \'=\', \'b\')->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IN (\'a\', \'b\') or `b` IN (\'c\', \'d\')',
                'DB::table(\'table\')->whereIn(\'a\', [\'a\', \'b\'])->orWhereIn(\'b\', [\'c\', \'d\'])->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` NOT IN (\'a\', \'b\') or `b` NOT IN (\'c\', \'d\')',
                'DB::table(\'table\')->whereNotIn(\'a\', [\'a\', \'b\'])->orWhereNotIn(\'b\', [\'c\', \'d\'])->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` LIKE \'%a%\'',
                'DB::table(\'table\')->where(\'a\', \'LIKE\', \'%a%\')->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NULL and `b` IS NULL',
                'DB::table(\'table\')->whereNull(\'a\')->whereNull(\'b\')->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NULL or `b` IS NULL',
                'DB::table(\'table\')->whereNull(\'a\')->orWhereNull(\'b\')->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NOT NULL and `b` IS NOT NULL',
                'DB::table(\'table\')->whereNotNull(\'a\')->whereNotNull(\'b\')->get()',
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NOT NULL or `b` IS NOT NULL',
                'DB::table(\'table\')->whereNotNull(\'a\')->orWhereNotNull(\'b\')->get()',
            ],
            [
                'SELECT `a` as `b`  FROM `table`',
                'DB::table(\'table\')->select(\'a AS b\')->get()',
            ],
            [
                'SELECT a as b  FROM `table`',
                'DB::table(\'table\')->select(\'a AS b\')->get()',
            ],
            [
                'SELECT * FROM table LIMIT 10',
                'DB::table(\'table\')->take(10)->get()',
            ],
            [
                'SELECT * FROM `table` LIMIT 5, 10',
                'DB::table(\'table\')->skip(5)->take(10)->get()',
            ],
            [
                'SELECT * FROM table ORDER BY id',
                'DB::table(\'table\')->orderBy(\'id\')->get()',
            ],
            [
                'SELECT * FROM table ORDER BY id DESC',
                'DB::table(\'table\')->orderBy(\'id\', \'DESC\')->get()',
            ],
            [
                'SELECT * FROM table GROUP BY id',
                'DB::table(\'table\')->groupBy(\'id\')->get()',
            ],
            [
                'SELECT * FROM table GROUP BY `id`',
                'DB::table(\'table\')->groupBy(\'id\')->get()',
            ],
            [
                'SELECT * FROM table LEFT JOIN `join_table` ON `table`.`id` = `join_table`.`table_id`',
                'DB::table(\'table\')->leftJoin(\'join_table\', \'table.id\', \'=\', \'join_table.table_id\')->get()',
            ],
            [
                'SELECT * FROM table RIGHT JOIN `join_table` ON `table`.`id` = `join_table`.`table_id`',
                'DB::table(\'table\')->rightJoin(\'join_table\', \'table.id\', \'=\', \'join_table.table_id\')->get()',
            ],
            [
                'SELECT * FROM table LEFT JOIN `join_table` `jt` ON `table`.`id` = `jt`.`table_id`',
                'DB::table(\'table\')->leftJoin(\'join_table AS jt\', \'table.id\', \'=\', \'jt.table_id\')->get()',
            ],
            [
                'SELECT * FROM table LEFT JOIN `join_table` AS `jt` ON `table`.`id` = `jt`.`table_id`',
                'DB::table(\'table\')->leftJoin(\'join_table AS jt\', \'table.id\', \'=\', \'jt.table_id\')->get()',
            ],
            [
                'SELECT count(`c`)  FROM table',
                'DB::table(\'table\')->select(DB::raw(\'COUNT(`c`)\'))->get()',
            ],
            [
                'SELECT count(`c`) as c FROM table',
                'DB::table(\'table\')->select(DB::raw(\'COUNT(`c`) AS c\'))->get()',
            ],
        ];
    }
}
