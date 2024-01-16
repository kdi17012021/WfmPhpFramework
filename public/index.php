<?php

if(PHP_MAJOR_VERSION < 8){
    die('небходима восьмая пхп или новее');
};

require_once dirname(__DIR__) . '/config/init.php';


new \wfm\App();

//\wfm\App::$app->setProperty('test', 'TEST');
//var_dump(\wfm\App::$app->getProperties());
// echo \wfm\App::$app->getProperty('pagination');

throw new Exception('Ошибочка вышла', 404);

//echo $ttteessst;