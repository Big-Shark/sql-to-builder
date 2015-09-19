<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\BuilderClass;

class ExampleTest extends \PHPUnit_Framework_TestCase
{

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
                'DB::table(\'table\')->get()'
            ],
            [
                'SELECT * FROM `table`',
                'DB::table(\'table\')->get()'
            ],
            [
                'SELECT `*` FROM table',
                'DB::table(\'table\')->get()'
            ],
            [
                'SELECT a, b, c  FROM table',
                'DB::select(\'a\', \'b\', \'c\')->table(\'table\')->get()'
            ],
            [
                'SELECT `a`, `b`, `c`  FROM table',
                'DB::select(\'a\', \'b\', \'c\')->table(\'table\')->get()'
            ],
            [
                'SELECT *  FROM table WHERE `a` = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->get()'
            ],
            [
                'SELECT *  FROM table WHERE a = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` = 1 and `b` = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->where(\'b\', \'=\', 1)->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` = 1 or `b` = 1',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->orWhere(\'b\', \'=\', 1)->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` = 1 and `b` = \'b\'',
                'DB::table(\'table\')->where(\'a\', \'=\', 1)->where(\'b\', \'=\', \'b\')->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IN (\'a\', \'b\') or `b` IN (\'c\', \'d\')',
                'DB::table(\'table\')->whereIn(\'a\', [\'a\', \'b\'])->orWhereIn(\'b\', [\'c\', \'d\'])->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` NOT IN (\'a\', \'b\') or `b` NOT IN (\'c\', \'d\')',
                'DB::table(\'table\')->whereNotIn(\'a\', [\'a\', \'b\'])->orWhereNotIn(\'b\', [\'c\', \'d\'])->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` LIKE \'%a%\'',
                'DB::table(\'table\')->where(\'a\', \'LIKE\', \'%a%\')->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NULL and `b` IS NULL',
                'DB::table(\'table\')->whereNull(\'a\')->whereNull(\'b\')->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NULL or `b` IS NULL',
                'DB::table(\'table\')->whereNull(\'a\')->orWhereNull(\'b\')->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NOT NULL and `b` IS NOT NULL',
                'DB::table(\'table\')->whereNotNull(\'a\')->whereNotNull(\'b\')->get()'
            ],
            [
                'SELECT *  FROM `table` WHERE `a` IS NOT NULL or `b` IS NOT NULL',
                'DB::table(\'table\')->whereNotNull(\'a\')->orWhereNotNull(\'b\')->get()'
            ],
            [
                'SELECT `a` as `b`  FROM `table`',
                'DB::select(\'a as b\')->table(\'table\')->get()'
            ],
            [
                'SELECT a as b  FROM `table`',
                'DB::select(\'a as b\')->table(\'table\')->get()'
            ],
            [
                'SELECT * FROM `tableA` LEFT JOIN `tableB` ON `tableA`.id = `tableB`.`tableA_id`',
                'DB::table(\'tableA\')->join(\'tableB\', \'tableA.id\', \'=\', \'tableB.tableA_id\')->get()'
            ],
            [
                'SELECT * FROM table LIMIT 10',
                'DB::table(\'table\')->take(10)->get()'
            ],
            [
                'SELECT * FROM `table` LIMIT 5, 10',
                'DB::table(\'table\')->skip(5)->take(10)->get()'
            ],
            [
                'SELECT * FROM table ORDER BY id',
                'DB::table(\'table\')->orderBy(\'id\')->get()'
            ],
            [
                'SELECT * FROM table ORDER BY id DESC',
                'DB::table(\'table\')->orderBy(\'id\', \'DESC\')->get()'
            ],
        ];
    }
}
