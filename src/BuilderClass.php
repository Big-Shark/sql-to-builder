<?php

namespace BigShark\SQLToBuilder;

use PHPSQLParser\PHPSQLParser;

class BuilderClass
{
    protected $sql = null;
    public function __construct($sql)
    {
        $this->sql = $sql;
    }

    /**
     * @return string
     */
    public function convert()
    {
        $parser = new PHPSQLParser();
        $parsed = $parser->parse($this->sql);
        $q = [];
        if(isset($parsed['FROM']))
        {
            $q['table'] = $this->parseFrom($parsed['FROM']);
        }

        if(isset($parsed['SELECT']))
        {
            $q['select'] = $this->parseSelect($parsed['SELECT']);
        }

        if(isset($parsed['WHERE']))
        {
            $where = $this->parseWhere($parsed['WHERE']);
            $q['where'] = implode($where, '->');
        }

        return "DB::".implode($q,'->')."->get()";//$this->sql;
    }

    protected function parseFrom($from)
    {
        if( isset($from[0]) and 'table' === $from[0]['expr_type'])
        {
            return "table('".$from[0]['table']."')";
        }
        throw new \Exception('Not valid from');
    }

    protected function parseSelect($select)
    {
        $s = [];
        foreach($select as $item)
        {
            if( 'colref' === $item['expr_type'])
            {
                $s[] = $item['base_expr'];
            }
        }
        if( $s )
        {
            return "select('".implode($s, "', '")."')";
        }
        throw new \Exception('Not valid select');
    }

    protected function parseWhere($where)
    {
        $i = 0;
        $w = [];
        foreach($where as $key=>$item)
        {
            if('colref' === $item['expr_type'])
            {
                $w[$i]['args']['col'] = $item['base_expr'];
            }
            elseif('const' === $item['expr_type'])
            {
                $w[$i]['args']['value'] = $item['base_expr'];
            }
            elseif('operator' === $item['expr_type'] AND 'or' !== $item['base_expr'] AND 'and' !== $item['base_expr'] )
            {
                $w[$i]['args']['operator'] = $item['base_expr'];
            }
            elseif('operator' === $item['expr_type'] AND ( 'or' === $item['base_expr'] OR 'and' === $item['base_expr'] ))
            {
                $i++;
                $w[$i]['connector'] = $item['base_expr'];
            }
            //dump($w);
        }

        if( $w )
        {
            $r = [];
            foreach($w as $where)
            {
                //dump($where);
                if( ! isset($where['connector']))
                {
                    $where['connector'] = 'and';
                }
                if( ! is_numeric($where['args']['value'])  )
                {
                    $where['args']['value'] = "'".$where['args']['value']."'";
                }
                $r[] =  $where['connector'] . "Where('" . $where['args']['col'] . "', '" . $where['args']['operator'] . "', " . $where['args']['value'] . ")";
            }
            return $r;
        }
        throw new \Exception('Not valid where');
    }
}
