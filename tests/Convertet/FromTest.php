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

    public function testNotValid()
    {
        try {
            $result = $this->converter->convert([[],[]]);
        } catch(\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Not valid from');
        }

        try {
            $result = $this->converter->convert([['expr_type' => 'foo']]);
        } catch(\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Not valid from');
        }
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
}


