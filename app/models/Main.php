<?php

namespace app\models;
use RedBeanPHP\R;
use wfm\Model;

class Main extends Model
{
    public function get_names()
    {
        return R::findAll('name');
    }
}