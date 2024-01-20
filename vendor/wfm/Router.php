<?php
//вообще вся эта штука для того чтобы в адресной строке типа https://new-ishop.loc/product/view/apple
//работали пути домен/контроллер(класс)/акшон(метод)/параметр
namespace wfm;

class Router
{
    protected static array $routes = [];//сюда заходит таблица маршрутов когда
    protected static array $route = [];

    public static function add($regexp, $route = []){//(шаблон регулярного вырадения, соответствие)
        // тут будут передаваться соответствия в регулярных выражениях
        //в файле routes.php сюда заходит через функцию add регулярные выражения и записываются в $routes
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

    public static function dispatch($url)//получаетто что после домена запуускается в классе App
    {
        if (self::matchRoute($url)) {
            echo 'OK';
        } else {
            echo 'NO';
        }
    }


    public static function matchRoute($url): bool//будем сравнивать с таблицей маршрутов в routes.php контроллеры и экшоны
    {
        foreach (self::$routes as $pattern => $route) {//перебираем все что записали в файле routes.php
            if (preg_match("#{$pattern}#i", $url, $matches)) {//если соответствие  рег выражении найдено то запишет в массив $matches
                //# - ограничители шаблона
                //там еще особенные регулярки '^(?P<controller>[a-z-]+)/(?P<action>[a-z-]+)/?$' - они то что в скобках записывают в ключи массива
                foreach ($matches as $k => $v) {//сам по себе массив матчес кривоват
                    //поэтому мы его перебирем и вытащим только строковые ключи а не числовые
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                //ну а дальше шатаем этот массив
                if (empty($route['action'])) {//экшона может и не быть а есть только коyтроллер типа new-ishop.loc
                    // //Router::add('^$', ['controller' => 'Main']);
                    //это чтобы показывать главную страницу при пустрой странице запроса
                    $route['action'] = 'index';
                }
                if (!isset($route['admin_prefix'])) {//если в админ префикс(его может и не быть) ничего не попало то делаем провекр
                    $route['admin_prefix'] = '';//записываем пустую строку в любом случае даже если его нет
                } else {//а если там что то есть
                    $route['admin_prefix'] = '\\';//слеши для пространства имен
                }
                debug($route);
                $route['controller'] = self::upperCamelCase($route['controller']);//переименовываем и причесываем названия классов
                //потому что в имя контироллера может попасть new-product а так класс называть нельзя - надо NewProduct
                debug($route);
                return true;
            }
        }
        return false;
    }


    // CamelCase -так называются классы
    protected static function upperCamelCase($name): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));//третий параметр - это обработанная name
        //сначала делаем New Product а потом NewProduct
    }

    // camelCase - так называются методы
    protected static function lowerCamelCase($name): string
    {
        return lcfirst(self::upperCamelCase($name));
    }
}
