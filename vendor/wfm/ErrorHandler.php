<?php


namespace wfm;


class ErrorHandler
{

    public function __construct()
    {
        // https://habr.com/ru/post/161483/
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'errorHandler']);
        ob_start();//буферизируем ошибку и не выводим
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $this->logError($errstr, $errfile, $errline);
        $this->displayError($errno, $errstr, $errfile, $errline);
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();//получаем массив с последней ошибкой
        //проверяем что не пуста и тип ошибки соответствует тем которые мы можем обработать мы можем обработать
        // & - "побитовое" И
        if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            $this->logError($error['message'], $error['file'], $error['line']);
            ob_end_clean();
            $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
        } else {
            ob_end_flush();//выключаем буфер если ничего из этого
        }
    }

    public function exceptionHandler(\Throwable $e)//объект ошибки \Throwable - интерфейс
    {
        //берем из объекта ошибки данные и запускаем логгирование и отображение
        $this->logError($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    protected function logError($message = '', $file = '', $line = '')//по умолчанию пустые
    {
        file_put_contents(
            LOGS . '/errors.log',
            "[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n=================\n",
            FILE_APPEND);//не перезаписывать а дозаписывать
    }

    protected function displayError($errno, $errstr, $errfile, $errline, $responce = 500)
    {
        if ($responce == 0) {//если 0 то задаеем 404
            $responce = 404;
        }
        http_response_code($responce);//отправляет код ответа в заголовках
        if ($responce == 404 && !DEBUG) {//если 404 - страница не найдена И(&&) константа дебаг стоит 0 - выключена
            require WWW . '/errors/404.php';//через константу забираем файл
            die;
        }
        if (DEBUG) {//при включенном режиме отладки покажем дев
            require WWW . '/errors/development.php';
        } else {//ну а дальше понятно
            require WWW . '/errors/production.php';
        }
        die;
    }

}