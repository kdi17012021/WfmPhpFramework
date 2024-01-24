<?php
namespace app\controllers;

use wfm\Controller;//лежат в разных пространствах имен
class MainController extends Controller
{
    public function indexAction()
    {
//        var_dump($this->model);
//        echo __METHOD__;//app\controllers\MainController::indexAction
        $this->setMeta('Главная Страница', 'Описание написали', 'йа сеошник');
        $this->set(['test' => 'TEST VAR','name' => 'JONH']);
    }
}