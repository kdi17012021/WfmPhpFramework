<?php

if(PHP_MAJOR_VERSION < 8){
    die('небходима восьмая пхп или новее');
};

require_once dirname(__DIR__) . '/config/init.php';

echo 'privet';