<?php
namespace app\controllers;

use app\models\Main;

use app\widgets\menu\Menu;
use wfm\App;
use wfm\Cache;
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


//                $test = 'Hello';
//        $cache = Cache::getInstance();//cинглтон
////        $cache->set('test', $test, 30);
//        var_dump($cache->get('test'));
//        var_dump($test);

        $lang = App::$app->getProperty('language');
        $slides = R::findAll('slider');//получили из базы пути к каждому слайдосу

        $products = $this->model->get_hits($lang, 6);// в свойстве модель(класса контроллер самого базового) роутер записывает путь к классу модели. В данном случае -  app/models/Main.php. в котором гет хитс
        $this->set(compact('slides', 'products'));//прокидываем в вид чтобы использовать в main/index.php
        $this->setMeta(___('main_index_meta_title'), ___('main_index_meta_description'), ___('main_index_meta_keywords'));
    }
}