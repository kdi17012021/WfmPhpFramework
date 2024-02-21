<?php

namespace app\widgets\language;
use wfm\App;
use RedBeanPHP\R;

class Language
{

    protected $tpl;
    protected $languages;
    protected $language;// активный язык

    public function __construct()
    {
        $this->tpl = __DIR__ . '/lang_tpl.php';
        $this->run();
    }


    protected function run()
    {
        $this->languages = App::$app->getProperty('languages');
        $this->language = App::$app->getProperty('language');
        echo $this->getHtml();
    }

    public static function getLanguages(): array
    {
        return R::getAssoc("SELECT code, title, base, id FROM language ORDER BY base DESC");//в редбине ключом будет первый параметр - code
    }

    public static function getLanguage($languages) {
        //тут у нас в урле будет что то типа http://new-ishop.loc/en/product/canon-eos-5d
        //нам надо будет получить то что в этой строке и проверить на то, есть ли в базе такой язык
        //если в урле ничего не написано то будет язык по умолчанию
        //если такого языка нет то выбросим исключение
        //короче по итогу мы берем и записываем в $key - ключ языка который будет применен

        $lang = App::$app->getProperty('lang');
        if($lang && array_key_exists($lang, $languages)){
            $key = $lang;
        }elseif (!$lang){
            $key = key($languages);//первый базовый язык по умолчанию если в $lang ничего не попало
        }else{
            $lang = h($lang);
            throw new \Exception("Not found language {$lang}", 404);
        }

        $lang_info = $languages[$key];
        $lang_info['code'] = $key;
        return $lang_info;

    }

    protected function getHtml(): string
    {
        ob_start();//буферизация вывода для этого метода - походу используется она перед какими то подключениями файлов
        // чтобы потом использовать этот файл(верстку) в другом месте - в функции run()
        require_once $this->tpl;
        return ob_get_clean();
    }


}
