<?php

include __DIR__.'/../vendor/autoload.php';

$sql = 'select `users`.`id` from `users`';
$builder = new \BigShark\SQLToBuilder\BuilderClass($sql);
$result = $builder->convert();
echo $result; //"DB::table('some_table')->select('a', 'b', 'c')->where('d', '>', 5)->get()"
