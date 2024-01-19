<?php

namespace wfm;

class App
{
    public static $app;

    public function __construct(){
        $query = trim(urldecode($_SERVER['QUERY_STRING']), '/');//берем строку запроса и обрезаем пробелы и слеш
        new ErrorHandler();
        self::$app = Registry::getInstance();//один экземпляр класса по синглтону
        $this->getParams();
        Router::dispatch($query);
    }

    protected function getParams(){
        $params = require_once CONFIG . '/params.php';
        if (!empty($params)){
            foreach ($params as $k => $v){
                self::$app->setProperty($k, $v);
            }
        }
    }



}