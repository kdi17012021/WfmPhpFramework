<?php
//вообще вся эта штука для того чтобы в адресной строке типа https://new-ishop.loc/product/view/apple
//работали пути домен/контроллер(класс)/акшон(метод)/параметр
namespace wfm;

class Router
{
    protected static array $routes = [];//сюда заходит таблица маршрутов
    protected static array $route = [];

    public static function add($regexp, $route = []){//(шаблон регулярного вырадения, соответствие)
        // тут будут передаваться соответствия в регулярных выражениях
        self::$routes[$regexp] = $route;
    }




    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRoute(): array
    {
        return self::$route;
    }

    public static function dispatch($url)//получаетто что после домена
    {
        var_dump($url);
    }
}
