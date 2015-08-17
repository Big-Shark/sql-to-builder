<?php

include __DIR__ . '/../vendor/autoload.php';

$sql = 'SELECT a, b, c  FROM some_table WHERE d > 5';
$builder = new \BigShark\SQLToBuilder\BuilderClass($sql);
$result = $builder->convert();
echo $result; //"DB::table('some_table')->select('a', 'b', 'c')->where('d', '>', 5)->get()"

