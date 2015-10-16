<?php

namespace app\modules\man\controllers;

use app\modules\man\models\LoginForm;
use app\modules\man\Module;
use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = 'main';

    /**
     * 显示中央控制台主面板
     * @return string
     */
    public function actionIndex()
    {
        if ($this->getManager()->isGuest) {
            return $this->redirect(['default/login']);
        }

        /* 根据所有模块权限，获取所有模块man配置信息 */
        $modules = Module::getInstance()->moduleManager->getModules();
        $menu = [];
        foreach ($modules as $id => $m) {
            if ($m['man'] !== false && $this->getManager()->can($m['man']['main']['permission'])) {
                if (isset($m['man']['sub'])) {
                    // 转换sub下url格式
                    foreach ($m['man']['sub'] as $name => $v) {
                        $m['man']['sub'][$name]['url'] = ["/$id/{$v['url']}"];
                        if(!$this->getManager()->can($m['man']['sub'][$name]['permission']))
                            unset($m['man']['sub'][$name]);
                    }
                }
                // 转换url格式
                $m['man']['main']['url'] = ["/$id/{$m['man']['main']['url']}"];
                $menu[$id] = $m['man'];
            }
        }
        return $this->render('index', ['menu' => $menu]);
    }

    /**
     * 中央控制台登录入口
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!$this->getManager()->isGuest) {
            return $this->redirect(['default/index']);
        }
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['default/index']);
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * 退出登录中央控制台
     * @return \yii\web\Response
     */
    public function actionLogout(){
        $this->getManager()->logout();

        return $this->redirect(['default/login']);
    }

    /**
     * 进入中控台后默认页面
     * @return string
     */
    public function actionInfo(){
        return $this->render('info');
    }

    /**
     * 获取管理员(manager)组件对象
     * @return \yii\web\User
     */
    protected function getManager()
    {
        return \Yii::$app->manager;
    }
}
