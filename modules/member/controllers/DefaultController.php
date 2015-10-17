<?php

namespace app\modules\member\controllers;

use app\modules\member\models\LoginForm;
use app\modules\member\models\SignupForm;
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
            $this->sendFlashMessage('您当前已是登录状态！');
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            $this->sendFlashMessage('您已成功登录！');
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
        \Yii::$app->user->logout(false);
        $this->sendFlashMessage('您已安全退出登录！');
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

        $model = new SignupForm();
        if ($model->load(\Yii::$app->request->post()) && $model->signup()) {
            $this->sendFlashMessage('注册成功！');
            return $this->redirect(['/site/login']);
        }
        return $this->render('signup', ['model' => $model]);
    }

    /**
     * 发送通知信息到下一个请求页面
     * @param $message string 要发送的信息
     */
    protected function sendFlashMessage($message){
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], $message);
    }
}
