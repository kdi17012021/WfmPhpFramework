<?php
namespace app\controllers;

use wfm\Controller;//лежат в разных пространствах имен
class MainController extends Controller
{
    public function indexAction()
    {
        var_dump($this->model);
        echo __METHOD__;//app\controllers\MainController::indexAction
    }
}