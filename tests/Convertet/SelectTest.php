<?php

namespace BigShark\SQLToBuilder\Test\Converter;

class SelectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var null|\BigShark\SQLToBuilder\Converter\SelectConverter
     */
    protected $converter = null;

    public function setUp()
    {
        $this->converter = new \BigShark\SQLToBuilder\Converter\SelectConverter();
    }

    public function testNotValid()
    {
        try {
            $this->converter->convert([['expr_type' => 'foo', 'base_expr' => 'bar']]);
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Not valid select');
        }
    }

    public function testStar()
    {
        $result = $this->converter->convert([['base_expr' => '*']]);
        $this->assertEquals($result, []);
    }

    public function testSimple()
    {
        $select = ['expr_type' => 'colref', 'base_expr' => 'a'];

        $result = $this->converter->convert([$select]);
        $this->assertEquals($result, [['name' => 'select', 'args' => ['a']]]);

        $select['base_expr'] = '`a`';
        $select['no_quotes'] = ['parts' => ['a']];

        $result = $this->converter->convert([$select]);
        $this->assertEquals($result, [['name' => 'select', 'args' => ['a']]]);
    }

    public function testMultiSelect()
    {
        $selectA = ['expr_type' => 'colref', 'base_expr' => 'a'];
        $selectB = ['expr_type' => 'colref', 'base_expr' => 'b'];

        $result = $this->converter->convert([$selectA, $selectB]);
        $this->assertEquals($result, [['name' => 'select', 'args' => ['a', 'b']]]);

        $selectA['base_expr'] = '`a`';
        $selectA['no_quotes'] = ['parts' => ['a']];

        $selectB['base_expr'] = '`b`';
        $selectB['no_quotes'] = ['parts' => ['b']];

        $result = $this->converter->convert([$selectA, $selectB]);
        $this->assertEquals($result, [['name' => 'select', 'args' => ['a', 'b']]]);
    }

    public function testAliases()
    {
        $select = ['expr_type' => 'colref', 'base_expr' => 'a'];
        $select['alias'] = ['name' => 'b'];

        $result = $this->converter->convert([$select]);
        $this->assertEquals($result, [['name' => 'select', 'args' => ['a as b']]]);

        $select['base_expr'] = '`a`';
        $select['no_quotes'] = ['parts' => ['a']];

        $result = $this->converter->convert([$select]);
        $this->assertEquals($result, [['name' => 'select', 'args' => ['a as b']]]);

        $select['alias'] = ['name' => '`b`'];
        $select['alias']['no_quotes'] = ['parts' => ['b']];

        $result = $this->converter->convert([$select]);
        $this->assertEquals($result, [['name' => 'select', 'args' => ['a as b']]]);
    }
}
