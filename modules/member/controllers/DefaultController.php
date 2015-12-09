<?php

namespace app\modules\member\controllers;

use app\modules\member\models\LoginForm;
use app\modules\member\models\SignupForm;
use app\modules\member\Module;
use app\base\Event;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 用户登陆，成功后则返回上一页面
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        Event::trigger(Module::className(), Module::EVENT_BEFORE_LOGIN);

        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * 用户退出，成功后返回主页
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Event::trigger(Module::className(), Module::EVENT_BEFORE_LOGOUT);

        \Yii::$app->user->logout(false);

        Event::trigger(Module::className(), Module::EVENT_AFTER_LOGOUT);

        return $this->goHome();
    }

    /**
     * 用户注册，成功后跳转登录页面
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        Event::trigger(Module::className(), Module::EVENT_BEFORE_SIGNUP);

        $model = new SignupForm();
        if ($model->load(\Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['/site/login']);
        }

        return $this->render('signup', ['model' => $model]);
    }

}
