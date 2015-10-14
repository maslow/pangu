<?php

namespace app\modules\man\controllers;

use app\modules\man\Module;
use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = 'main';

    /**
     * 显示中央控制台
     * @return string
     */
    public function actionIndex()
    {
        /* 获取所有模块man配置信息 */
        $modules = Module::getInstance()->moduleManager->getModules();
        $menu = [];
        foreach ($modules as $id => $m) {
            if ($m['man'] !== false) {
                if (isset($m['man']['sub'])) {
                    // 转换sub下url格式
                    foreach ($m['man']['sub'] as $name => $v) {
                        $m['man']['sub'][$name]['url'] = ["/$id/{$v['url']}"];
                    }
                }
                // 转换url格式
                $m['man']['main']['url'] = ["/$id/{$m['man']['main']['url']}"];
                $menu[$id] = $m['man'];
            }
        }
        return $this->render('index', ['menu' => $menu]);
    }

    public function actionLogin()
    {

    }
}
