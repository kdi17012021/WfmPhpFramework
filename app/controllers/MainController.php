<?php
namespace app\controllers;

use wfm\Controller;//лежат в разных пространствах имен
use RedBeanPHP\R;
class MainController extends Controller
{
    public function indexAction()
    {
        $names = $this->model->get_names();
        $one_name = R::getRow( 'SELECT * FROM name WHERE id = 2');//регистрочувствителен
//        debug($names);
//        var_dump($this->model);
//        echo __METHOD__;//app\controllers\MainController::indexAction
        $this->setMeta('Главная Страница', 'Описание написали', 'йа сеошник');
        $this->set(['test' => 'TEST VAR','name' => 'JONH']);
        $this->set(compact('names'));
    }
}