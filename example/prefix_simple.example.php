<?php

include __DIR__.'/../vendor/autoload.php';

$sql = 'SELECT some_table.a, some_table.b, some_table.c FROM some_table';

$builder = new \BigShark\SQLToBuilder\BuilderClass($sql);
$result = $builder->convert();
echo $result; //"DB::select('some_table.a', 'some_table.b', 'some_table.c')->table('some_table')->get()"
