<?php

namespace app\modules\rbac\controllers;

class ManagerController extends \yii\web\Controller
{
    public $layout = 'manager';
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPermissions()
    {
        return $this->render('permissions');
    }

    public function actionRoles()
    {
        return $this->render('roles');
    }

}
