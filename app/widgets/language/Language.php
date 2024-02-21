<?php

namespace app\widgets\language;

use RedBeanPHP\R;

class Language
{

    protected $tpl;
    protected $languages;
    protected $language;// активный язык

    public function __construct()
    {
        $this->tpl = __DIR__ . 'lang_tpl.php';
        $this->run();
    }

    protected function run()
    {

    }

    public static function getLanguages(): array
    {
        return R::getAssoc("SELECT code, title, base, id FROM language ORDER BY base DESC");//в редбине ключом будет первый параметр - code
    }

    public static function getLanguage($languages)
    {

    }

}