<?php
//тут надо знать регулярные выражения
use wfm\Router;//чтобы везде не писать пространство имен
//под админку более конкретные правила должны находиться выше чем более общие
Router::add('^admin/?$', ['controller' => 'Main', 'action' => 'index', 'admin_prefix' => 'admin']);
//главная страница админки
Router::add('^admin/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['admin_prefix' => 'admin']);
//то же самое что и внизу но для админки

Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
//регулярное выражение от начала до конца строки ничего нет - просто new-ishop.loc/
Router::add('^(?P<controller>[a-z-]+)/(?P<action>[a-z-]+)/?$');
//() - карманы которые записывают в массив с ключами контроллер / вью что угодно