<?php

if (file_exists(__DIR__.'/../../../autoload.php')) {
    require_once __DIR__.'/../../../autoload.php';
} else {
    require_once __DIR__.'/../vendor/autoload.php';
}

if (!isset($_SERVER['argv'][1])) {
    throw new Exception('The argument is not specified or is incorrect');
}

$builder = new \BigShark\SQLToBuilder\BuilderClass($_SERVER['argv'][1]);
echo $builder->convert();
