<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * 错误处理请求与验证码请求
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    /**
     * 网站首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        // user login or signup comes here
    }
}
