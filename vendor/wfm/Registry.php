<?php

namespace wfm;

class Registry
{
    use TSingleton;
    //импортировали трейт

    protected static array $properties = [];
    //если статик то можно обратиться только как к классу через :: а не через -> или this

    public static function setProperty($name, $value)
    {
        self::$properties[$name] = $value;
    }


    public function getProperty($name)
    {
        return self::$properties[$name] ?? null;
        //если что то есть в этом ключе в массиве то вернем его а если нет то вернем налл
    }



    public function getProperties(): array
    {
        return self::$properties;
    }
}