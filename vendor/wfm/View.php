<?php


namespace wfm;


use RedBeanPHP\R;

class View
{

    public string $content = '';//контентная часть которая будет меняться внутри шаблона страницы

    public function __construct(//в пхп 8 можно определять свойства класса в конструкторе
        public $route,
        public $layout = '',
        public $view = '',
        public $meta = [],
    )
    {
        if (false !== $this->layout) {//шаблон может быть выставлен в фолс и тогда мы его не трогаем
            $this->layout = $this->layout ?: LAYOUT;//если пусто то шаблон по умолчанию а если что то было записано то это и оставим
        }
    }

    public function render($data)//отрисовка странички
    {
        if (is_array($data)) {
            extract($data);//создаст из ключей переменные - пиздатая функция
        }
        $prefix = str_replace('\\', '/', $this->route['admin_prefix']);//надо распечатывать что приходит в массивы чтобы такие баги с путями отлавливать
        //для путей нужен прямой слеш
        $view_file = APP . "/views/{$prefix}{$this->route['controller']}/{$this->view}.php";//заполняем путь к верстке данными которые пришли по длгому пути из контроллера
        if (is_file($view_file)) {
            ob_start();//не будем выводить а будем буферизировать вид
            require_once $view_file;//собственно буферизируем
            $this->content = ob_get_clean();// сам вид забираем из буфера и засовываем в свойство контент который будет отображаться в шаблоне
        } else {
            throw new \Exception("Не найден вид {$view_file}", 500);
        }

        if (false !== $this->layout) {//проверяем не отключен ли шаблон раньше 
            $layout_file = APP . "/views/layouts/{$this->layout}.php";
            if (is_file($layout_file)) {
                require_once $layout_file;
            } else {
                throw new \Exception("Не найден шаблон {$layout_file}", 500);
            }
        }

    }

    public function getMeta()
    {
        $out = '<title>' . h($this->meta['title']) . '</title>' . PHP_EOL;//h= htmlspecialchars
        $out .= '<meta name="description" content="' . h($this->meta['description']) . '">' . PHP_EOL;
        $out .= '<meta name="keywords" content="' . h($this->meta['keywords']) . '">' . PHP_EOL;
        return $out;
    }


    public function getDbLogs()
    {
        if(DEBUG){
            $logs = R::getDatabaseAdapter()
                ->getDatabase()
                ->getLogger();
            $logs = array_merge(
                $logs->grep('SELECT'),//регистрочувствителен
                $logs->grep('select'),//регистрочувствителен
                $logs->grep('INSERT'),
                $logs->grep('UPDATE'),
                $logs->grep('DELETE'));
            debug($logs);
        }
    }


    public function getPart($file, $data = null){
        if(is_array($data)){
            extract($data);
        }
        $file = APP . "/views/{$file}.php";
        if (is_file($file)){
            require $file;//может быть что в разных местах несколько раз была возможность подключить эту часть
        }else{
            echo 'File {$file} not found';
        }
    }
}