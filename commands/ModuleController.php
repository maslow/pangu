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
        $this->stdout("更新所有模块信息...");
        $this->installModules();
        $this->stdout("\n更新模块信息完成!\n");
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
            $this->stdout("模块({$id})不存在!\n");
        }
    }


    /**
     * 配置并安装所有模块
     * @throws \yii\base\ErrorException
     */
    protected function installModules(){
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
    }

    protected function installModule($id){
        $this->stdout("\n....正在安装模块({$id}) ...");
        $migrationPath = $this->getMigrationPath($id);
        $migrationTable = $this->getMigrationTableName($id);
        $cmd = "php yii migrate/up --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
        system($cmd);
        $this->stdout("....完成!");
    }

    protected function uninstallModule($id){
        $this->stdout("正在卸载模块{$id} ...");
        $migrationPath = $this->getMigrationPath($id);
        $migrationTable = $this->getMigrationTableName($id);
        $cmd = "php yii migrate/to 0 --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
        system($cmd);
        $this->stdout("完成！\n");
        if($this->confirm("是否删除该模块目录？")){
            $path = $this->getModuleManager()->getPath($id);
            FileHelper::removeDirectory($path);
            $this->stdout("已删除`{$id}`模块目录{$path}！",Console::FG_GREEN);
        }else{
            $this->stdout("未删除`{$id}`模块目录，请手动删除!\n",Console::FG_YELLOW);
        }
    }

    /**
     * 获取模块管理组件
     * @return \app\modules\ModuleManager
     */
    protected function getModuleManager(){
        return \Yii::$app->moduleManager;
    }

    /**
     * 获取指定模块(module)数据迁移(migration)表名
     * @param $id string 模块id
     * @return string
     */
    protected function getMigrationTableName($id){
        return "{{%migration_{$id}}}";
    }

    /**
     * 获取指定模块(module)数据迁移(migration)目录
     * @param $id string 模块id
     * @return string
     */
    protected function getMigrationPath($id){
        return $this->getModuleManager()->moduleRoot."/{$id}/migrations";
    }
}
