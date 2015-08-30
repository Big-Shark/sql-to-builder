<?php

namespace BigShark\SQLToBuilder\Test;

use BigShark\SQLToBuilder\BuilderClass;
use BigShark\SQLToBuilder\Generator;

class WhereTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|\BigShark\SQLToBuilder\Converter\WhereConverter
     */
    protected $converter = null;

    protected $baseWhere = [];

    public function setUp()
    {
        $this->converter = new \BigShark\SQLToBuilder\Converter\WhereConverter(new Generator('$db'));

        $this->baseWhere = [
            [
                'expr_type' => 'colref',
                'base_expr' => 'a',
            ],
            [
                'expr_type' => 'operator',
                'base_expr' => '=',
                'sub_tree' => false,
            ],
            [
                'expr_type' => 'const',
                'base_expr' => 1,
                'sub_tree' => false,
            ]
        ];
    }

    public function testSimple()
    {
        $result = $this->converter->convert($this->baseWhere);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '=', '1']]]);
    }

    public function testQuotes()
    {
        $where = $this->baseWhere;
        $where[0] =  [
            'expr_type' => 'colref',
            'base_expr' => '`a`',
            'no_quotes' =>
            [
                'parts' => ['a'],
            ],
        ];

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '=', '1']]]);
    }

    public function testStringValue()
    {
        $where = $this->baseWhere;
        $where[2]['base_expr'] = '\'a\'';

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '=', 'a']]]);
    }

    public function testGT()
    {
        $where = $this->baseWhere;
        $where[1]['base_expr'] = '>';

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '>', '1']]]);
    }

    public function testGTE()
    {
        $where = $this->baseWhere;
        $where[1]['base_expr'] = '>=';

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '>=', '1']]]);
    }

    public function testLT()
    {
        $where = $this->baseWhere;
        $where[1]['base_expr'] = '<';

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '<', '1']]]);
    }

    public function testLTE()
    {
        $where = $this->baseWhere;
        $where[1]['base_expr'] = '<=';

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '<=', '1']]]);
    }

    public function testNe()
    {
        $where = $this->baseWhere;
        $where[1]['base_expr'] = '!=';

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '!=', '1']]]);

        $where = $this->baseWhere;
        $where[1]['base_expr'] = '<>';

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '<>', '1']]]);
    }

    public function testIN()
    {
        $where = $this->baseWhere;
        $where[1]['base_expr'] = 'IN';
        $where[2] = [
            'expr_type' => 'in-list',
            'base_expr' => '(\'a\', \'b\')',
            'sub_tree' => [
                [
                    'expr_type' => 'const',
                    'base_expr' => '\'a\'',
                    'sub_tree' => false,
                ],
                [
                    'expr_type' => 'const',
                    'base_expr' => '\'b\'',
                    'sub_tree' => false,
                ],
            ],
        ];

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'whereIn', 'args' => ['a', ['a', 'b']]]]);

        $where = $this->baseWhere;
        $where[1]['base_expr'] = 'IN';
        $where[2] = [
            'expr_type' => 'in-list',
            'base_expr' => '(1, 2)',
            'sub_tree' => [
                [
                    'expr_type' => 'const',
                    'base_expr' => '1',
                    'sub_tree' => false,
                ],
                [
                    'expr_type' => 'const',
                    'base_expr' => '2',
                    'sub_tree' => false,
                ],
            ],
        ];

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'whereIn', 'args' => ['a', [1, 2]]]]);
    }

    public function testNotIN()
    {
        $where = $this->baseWhere;
        $where[1]['base_expr'] = 'NOT';
        $where[2] = [
            'expr_type' => 'operator',
            'base_expr' => 'IN',
            'sub_tree' => false,
        ];
        $where[3] = [
            'expr_type' => 'in-list',
            'base_expr' => '(\'a\', \'b\')',
            'sub_tree' => [
                [
                    'expr_type' => 'const',
                    'base_expr' => '\'a\'',
                    'sub_tree' => false,
                ],
                [
                    'expr_type' => 'const',
                    'base_expr' => '\'b\'',
                    'sub_tree' => false,
                ],
            ],
        ];

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'whereNotIn', 'args' => ['a', ['a', 'b']]]]);
    }

    public function testAnd()
    {
        $where = $this->baseWhere;
        $where[3] = [
            'expr_type' => 'operator',
            'base_expr' => 'AND',
            'sub_tree' => false,
        ];
        $where[4] = $where[0];
        $where[5] = $where[1];
        $where[6] = $where[2];

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '=', '1']], ['name' => 'where', 'args' => ['a', '=', '1']]]);
    }

    public function testOr()
    {
        $where = $this->baseWhere;
        $where[3] = [
            'expr_type' => 'operator',
            'base_expr' => 'OR',
            'sub_tree' => false,
        ];
        $where[4] = $where[0];
        $where[5] = $where[1];
        $where[6] = $where[2];

        $result = $this->converter->convert($where);
        $this->assertEquals($result, [['name' => 'where', 'args' => ['a', '=', '1']], ['name' => 'orWhere', 'args' => ['a', '=', '1']]]);

    }
}

