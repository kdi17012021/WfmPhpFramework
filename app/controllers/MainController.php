<?php
namespace app\controllers;

use app\models\Main;

use wfm\Controller;//лежат в разных пространствах имен
use RedBeanPHP\R;

/** @property Main $model */
class MainController extends AppController
{
    public function indexAction()
    {
//        $names = $this->model->get_names();
//        $one_name = R::getRow( 'SELECT * FROM name WHERE id = 2');//регистрочувствителен
////        debug($names);
////        var_dump($this->model);
////        echo __METHOD__;//app\controllers\MainController::indexAction
//        $this->setMeta('Главная Страница', 'Описание написали', 'йа сеошник');
//        $this->set(['test' => 'TEST VAR','name' => 'JONH']);
//        $this->set(compact('names'));

        $slides = R::findAll('slider');//получили из базы пути к каждому слайдосу

        $products = $this->model->get_hits(1, 6);
        $this->set(compact('slides', 'products'));//прокидываем в вид чтобы использовать в main/index.php
    }
}