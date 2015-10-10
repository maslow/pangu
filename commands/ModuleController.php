<?php

namespace app\commands;

use app\modules\ModuleManager;
use yii\console\Controller;


class ModuleController extends Controller
{
    public function actionList()
    {
        $mm = new ModuleManager();
        $modules = $mm->getModules();
        var_dump($modules);
    }

    public function actionView($id)
    {
        $mm = new ModuleManager();
        $m = $mm->getModule($id);
        var_dump($m);
    }

    public function actionUpdate(){
        $mm = new ModuleManager();
        $modules = $mm->getModules();
        $content = serialize($modules);
        file_put_contents(\Yii::getAlias('@app/config/modules.php'),$content);
    }
}
