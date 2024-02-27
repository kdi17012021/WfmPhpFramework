<?php


function debug($data, $die = false)
{
    echo '<pre>'. print_r($data,1) . '</pre>';
    if($die){
        die;
    }
}

function h($str)//защитка от инъекций кода от юзера
{
    return htmlspecialchars($str);
}


function redirect($http = false)//
{
    if ($http) {
        $redirect = $http;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
        //вернуть пользователя на ту страницу с которой он пришел
        //если существует адрес с которого он пришел то возьмем его - в противном случае отправим на главную страницу
    }
    header("Location: $redirect");//отправляем на то что в переменной $redirect
    die;
}



function base_url()
{
    return PATH . '/' . (\wfm\App::$app->getProperty('lang') ? \wfm\App::$app->getProperty('lang') . '/' : '');
}


/**
 * @param string $key Key of GET array
 * @param string $type Values 'i', 'f', 's'
 * @return float|int|string
 */
//функция будет забирать из массива гет нужную инфу и приводить к нужному типу
function get($key, $type = 'i')//может быть интеджером, флотом или строкой
{
    $param = $key;//тут тупо строка
    $$param = $_GET[$param] ?? '';//переменная переменной - то есть значение переменной $param - будет именем переменной $$param
    if ($type == 'i') {
        return (int)$$param;//приведение к типу
    } elseif ($type == 'f') {
        return (float)$$param;//приведение к типу
    } else {
        return trim($$param);//во всех остальных случаях мы предполагаем что это будет строка
    }
}

/**
 * @param string $key Key of POST array
 * @param string $type Values 'i', 'f', 's'
 * @return float|int|string
 */
//функция будет забирать из массива пост нужную инфу и приводить к нужному типу
function post($key, $type = 's')
{
    $param = $key;
    $$param = $_POST[$param] ?? '';
    if ($type == 'i') {
        return (int)$$param;
    } elseif ($type == 'f') {
        return (float)$$param;
    } else {
        return trim($$param);
    }
}

function __($key)
{
    echo \wfm\Language::get($key);
}

function ___($key)
{
    return \wfm\Language::get($key);
}