<?php


namespace app\controllers;

use wfm\App;
use app\widgets\Language\Language;
use app\models\AppModel;
use wfm\Controller;

class AppController extends Controller
{

    public function __construct($route)
    {
        parent::__construct($route);// чтобы не затереть родительский конструктор
        new AppModel();

        App::$app->setProperty('languages', Language::getLanguages());
        debug(App::$app->getProperty('languages'));

    }

}