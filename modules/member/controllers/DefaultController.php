<?php

namespace app\modules\member\controllers;

use app\modules\member\models\LoginForm;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->login()){
            return $this->goBack();
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignin()
    {
        return $this->render('signin');
    }

}
