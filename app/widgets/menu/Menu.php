<?php


namespace app\widgets\menu;


use RedBeanPHP\R;
use wfm\App;
use wfm\Cache;

class Menu
{

    // https://www.youtube.com/watch?v=fOMaYSmsiQU
    // https://www.youtube.com/watch?v=Qble3-723bs
    protected $data;//из базы ассоциативный массив из таблиц category c JOIN category_description cd

//[1] => Array
//(
//[parent_id] => 0
//[slug] => kompyutery
//[category_id] => 1
//[language_id] => 1
//[title] => Компьютеры
//[description] =>
//[keywords] =>
//[content] =>
//Сайт рыбатекст поможет дизайнеру, верст
//
//
//)
    protected $tree;//из этого ассоциативного массива получится дерево c дополнительным ключом children - в котором будут все дети (parent id не равен 0)

//[children] => Array
//(
//[4] => Array
//(
//[parent_id] => 3
//[slug] => mac
//[category_id] => 4
//[language_id] => 1
//[title] => Mac
//[description] =>
//[keywords] =>
//[content] => Сайт рыбатекст поможет дизайнеру, верстальщику, вебмастеру сгенерировать несколько абзацев более менее осмысленного текста рыбы на русском языке, а начинающему оратору отточить навык публичных выступлений в домашних условиях. При создании генератора мы использовали небезизвестный универсальный код речей. Текст генерируется абзацами случайным образом от двух до десяти предложений в абзаце, что позволяет сделать текст более привлекательным и живым для визуально-слухового восприятия.
//)
//
//[5] => Array
//(
//[parent_id] => 3
//[slug] => windows
//[category_id] => 5

    protected $menuHtml;//верстка меню которая кешируется
    protected $tpl;
    protected $container = 'ul';
    protected $class = 'menu';
    protected $cache = 3600;
    protected $cacheKey = 'ishop_menu';
    protected $attrs = [];
    protected $prepend = '';
    protected $language;

    public function __construct($options = []){
        $this->language = App::$app->getProperty('language');
        $this->tpl = __DIR__ . '/menu_tpl.php';
        $this->getOptions($options);
        $this->run();
    }

    protected function getOptions($options){
        foreach($options as $k => $v){
            if(property_exists($this, $k)){
                $this->$k = $v;
            }
        }
    }

    protected function run(){
        $cache = Cache::getInstance();
        $this->menuHtml = $cache->get("{$this->cacheKey}_{$this->language['code']}");

        if(!$this->menuHtml){
            $this->data = R::getAssoc("SELECT c.*, cd.* FROM category c 
                        JOIN category_description cd
                        ON c.id = cd.category_id
                        WHERE cd.language_id = ?", [$this->language['id']]);
            //            debug($this->data);
            $this->tree = $this->getTree();
//            debug($this->tree);
            $this->menuHtml = $this->getMenuHtml($this->tree);
            if($this->cache){
                $cache->set("{$this->cacheKey}_{$this->language['code']}", $this->menuHtml, $this->cache);
            }
        }

        $this->output();
    }

    protected function output(){
        $attrs = '';
        if(!empty($this->attrs)){
            foreach($this->attrs as $k => $v){
                $attrs .= " $k='$v' ";
            }
        }
        echo "<{$this->container} class='{$this->class}' $attrs>";
        echo $this->prepend;
        echo $this->menuHtml;
        echo "</{$this->container}>";
    }

    protected function getTree(){
        $tree = [];
        $data = $this->data;
        foreach ($data as $id=>&$node) {
            if (!$node['parent_id']){
                $tree[$id] = &$node;
            } else {
                $data[$node['parent_id']]['children'][$id] = &$node;
            }
        }
        return $tree;
    }

    protected function getMenuHtml($tree, $tab = ''){
        $str = '';
        foreach($tree as $id => $category){
            $str .= $this->catToTemplate($category, $tab, $id);
        }
        return $str;
    }

    protected function catToTemplate($category, $tab, $id){
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }

}