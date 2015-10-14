<?php

namespace app\modules\man\controllers;

use app\modules\man\Module;
use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = 'main';
    public function actionIndex()
    {
        return $this->render('index');
    }

    protected function getModuleList(){
        return Module::getInstance()->moduleManager->getModules();
    }
}
