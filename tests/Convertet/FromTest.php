<?php

namespace BigShark\SQLToBuilder\Test\Converter;

class FromTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|\BigShark\SQLToBuilder\Converter\FromConverter
     */
    protected $converter = null;

    public function setUp()
    {
        $this->converter = new \BigShark\SQLToBuilder\Converter\FromConverter();
    }

    public function testSimple()
    {
        $from = ['expr_type' => 'table', 'table' => 'table'];

        $result = $this->converter->convert([$from]);

        $this->assertEquals($result, [['name' => 'table', 'args' => ['table']]]);

        $from['table'] = '`table`';
        $from['no_quotes'] = ['parts' => ['table']];
        $result = $this->converter->convert([$from]);

        $this->assertEquals($result, [['name' => 'table', 'args' => ['table']]]);
    }

    public function testJoin()
    {
        $from = [
            [
                'expr_type' => 'table',
                'table'     => '`tableA`',
                'no_quotes' => [
                    'delim' => false,
                    'parts' => [
                        'tableA',
                    ],
                ],
                'alias'      => false,
                'hints'      => false,
                'join_type'  => 'JOIN',
                'ref_type'   => false,
                'ref_clause' => false,
                'base_expr'  => '`tableA`',
                'sub_tree'   => false,
            ],
            [
                'expr_type' => 'table',
                'table'     => '`tableB`',
                'no_quotes' => [
                    'delim' => false,
                    'parts' => [
                        'tableB',
                    ],
                ],
                'alias'      => false,
                'hints'      => false,
                'join_type'  => 'LEFT',
                'ref_type'   => 'ON',
                'ref_clause' => [
                    [
                        'expr_type' => 'colref',
                        'base_expr' => '`tableA`.id',
                        'no_quotes' => [
                            'delim' => '.',
                            'parts' => [
                                'tableA',
                                'id',
                            ],
                        ],
                        'sub_tree' => false,
                    ],
                    [
                        'expr_type' => 'operator',
                        'base_expr' => '=',
                        'sub_tree'  => false,
                    ],
                    [
                        'expr_type' => 'colref',
                        'base_expr' => '`tableB`.`tableA_id`',
                        'no_quotes' => [
                            'delim' => '.',
                            'parts' => [
                                'tableB',
                                'tableA_id',
                            ],
                        ],
                        'sub_tree' => false,
                    ],
                ],
                'base_expr' => '`tableB` ON `tableA`.id = `tableB`.`tableA_id`',
                'sub_tree'  => false,
            ],
        ];

        $result = $this->converter->convert($from);

        $table = ['name' => 'table', 'args' => ['tableA']];
        $join = ['name' => 'join', 'args' => ['tableB', 'tableA.id', '=', 'tableB.tableA_id']];
        $this->assertEquals($result, [$table, $join]);
    }
}
