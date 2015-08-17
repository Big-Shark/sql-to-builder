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
        $w = [];
        foreach($where as $key=>$item)
        {
            if('colref' === $item['expr_type'])
            {
                $w[] = "where('".$item['base_expr']."', '".$where[$key+1]['base_expr']."', ".$where[$key+2]['base_expr'].")";
                unset($where[$key+1], $where[$key+2]);
            }
        }
        if( $w )
        {
            return $w;
        }
        throw new \Exception('Not valid where');
    }
}
