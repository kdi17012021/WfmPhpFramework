<?php
//базовый класс контроллера в котором все повторяющиеся однотипных действий - подключения к бд и установки шаблонов
//этот класс будет наследоваться всеми нашими контроллерами
// задача этого класса брать инфу из модели и передавать ее в вид
namespace wfm;

abstract class Controller//абстрантный - от него нельзя создать объект а можно только наследоваться
{

//свойства для любого контроллера
    public array $data = [];//сюда будем загружать данные из модели и передавать их в вид - это основная задача контроллера
    public array $meta = ['title' => '', 'description' => '', 'keywords' => ''];//из контроллера в шаблон будем передавать мета данные
    public false|string $layout = '';// фолс это если надо будет отдать ответ на запрос без дополнительной верстки
    public string $view = '';// по умолчанию будет соответствовать названию экшона - через контроллер сможем переопределить название вида
    public string|object $model;// это объект который будет указывать на модель которая будет автоматически загружаться для данного контроллера



    public function __construct(public $route = [])//тут тупо принимаем параметр чтобы каждый раз не принимать
    {

        echo __METHOD__;
    }



    public function getModel()//создает экземпляр класса соответствующей модели
        //модель будет называться по имени контроллера
    {
        $model = 'app\models\\' . $this->route['admin_prefix'] . $this->route['controller'];//app\models\Main
        if (class_exists($model)){
            $this->model = new $model();
        }
    }

    public function getView()//записывает в свойство вью назавние экшона
    {
    //  $x ?: $z
    //means "if $x is true, then use $x; otherwise use $z".
        $this->view = $this->view ?: $this->route['action'];//если это непустая строка оставим ее а в противном случае по названию экшона

    }



    public function set($data)// помойка для разных данных чтобы потом вытаскивать в настоящих контроллерах и видах
    {
        $this->data = $data;
    }


    public function setMeta($title = '',$description = '',$keywords = '')//устанавливает мету для экземпляра класса
    {
        $this->meta = [
          'title'=> $title,
          'description' => $description,
          'keywords' => $keywords,
        ];
    }



}