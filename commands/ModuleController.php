<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;


class ModuleController extends Controller
{
    /**
     * 模块管理命令
     */
    public function actionUpdate(){
        $this->installModules();
    }

    /**
     * 卸载指定模块命令
     * @param $id string 指定模块的id
     */
    public function actionUninstall($id){
        /** @var \app\modules\ModuleManager $mm */
        $mm = \Yii::$app->moduleManager;
        if($mm->isExist($id)){
            $this->uninstallModule($id);
        }else{
            echo "模块({$id})不存在!\n";
        }
    }


    protected function installModules(){
        echo "更新所有模块信息...";
        $mm = $this->getModuleManager();
        $modules = $mm->getModules();
        foreach($modules as $id=>$m){
            $this->stdout("\n..模块({$id})");
            if($mm->hasMigration($id)){
                $this->installModule($id);
            }
        }
        $content = serialize($modules);
        file_put_contents(\Yii::getAlias('@app/config/modules.php'),$content);
        echo "\n更新模块信息完成!\n";
    }

    protected function installModule($id){
        $mm = $this->getModuleManager();
        echo "\n....正在安装模块({$id}) ...";
        $migrationPath = $mm->moduleRoot."/{$id}/migrations";
        $cmd = "php yii migrate/up --interactive=0 --migrationPath={$migrationPath}";
        exec($cmd);
        echo "....完成!";
    }

    protected function uninstallModule($id){
        $mm = $this->getModuleManager();
        echo "正在卸载模块{$id} ...";
        $migrationPath = $mm->moduleRoot."/{$id}/migrations";
        $cmd = "php yii migrate/down --interactive=0 --migrationPath={$migrationPath}";
        exec($cmd);
        echo "完成！\n";
        if($this->confirm("是否删除该模块目录？")){
            FileHelper::removeDirectory($mm->getPath($id));
            $this->stdout("已删除`{$id}`模块目录{$mm->getPath($id)}！",Console::FG_GREEN);
        }else{
            $this->stdout("未删除`{$id}`模块目录，请手动删除!\n",Console::FG_YELLOW);
        }
    }

    /**
     * @return \app\modules\ModuleManager
     */
    protected function getModuleManager(){
        return \Yii::$app->moduleManager;
    }
}
