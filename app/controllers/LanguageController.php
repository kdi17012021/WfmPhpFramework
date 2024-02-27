<?php


namespace app\controllers;


use wfm\App;

class LanguageController extends AppController
{

    public function changeAction()//у каждого конторллера есть экшн
        //задачей этого экшона (смотрим снизу) - сделать редирект на урл соответствующего выбранного языка
        //при клике на выбранный язык получаем такой урл http://new-ishop.loc/en/product/apple-cinema-30?lang=ru
        //в этом урле будут 2 части - текуший язык и запрашиваемый
        // и обе такие части надо будет проверить
    {
        $lang = get('lang', 's');

//        var_dump($lang);


        if ($lang) {
            if (array_key_exists($lang, App::$app->getProperty('languages'))) {//есть ли такой язык в списке доступных языков
                // отрезаем базовый URL
                $url = trim(str_replace(PATH, '', $_SERVER['HTTP_REFERER']), '/');

                // разбиваем на 2 части... 1-я часть - возможный бывший язык
                $url_parts = explode('/', $url, 2);//en/product/apple - переделываем эту строку в массив
                // ищем первую часть (бывший язык) в массиве языков - код языка с которого мы хотим переключиться
                if (array_key_exists($url_parts[0], App::$app->getProperty('languages'))) {//если запрошенный язык есть в массиве с доступными языками
                    // присваиваем первой части новый язык(из гет параметров - которые попадают в гет параметры через js, если он не является базовым
                    if ($lang != App::$app->getProperty('language')['code']) {// если запрашиваемый язык не равен коду текущего - проверяем что язык не базовый
                        $url_parts[0] = $lang;
                    } else {
                        // если это базовый язык - удалим язык из url
                        array_shift($url_parts);//удалит первый элемент массива
                    }
                } else {
                    // присваиваем первой части новый язык, если он не является базовым
                    if ($lang != App::$app->getProperty('language')['code']) {
                        array_unshift($url_parts, $lang);
                    }
                }

                $url = PATH . '/' . implode('/', $url_parts);
                redirect($url);
//                debug(App::$app->getProperty('languages'));
//                debug(App::$app->getProperty('language'));
//                die;
            }
        }
        redirect();
    }

}