<?php

namespace app\commands;

use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;


class ModuleController extends Controller
{
    /**
     * 模块管理命令
     */
    public function actionUpdate()
    {
        $this->stdout("更新所有模块信息...");
        $this->installModules();
        $this->stdout("\n更新模块信息完成!\n");

        $this->actionUpdatePermissions();
    }

    /**
     * 卸载指定模块命令
     * @param $id string 指定模块的id
     */
    public function actionUninstall($id)
    {
        $mm = $this->getModuleManager();
        if ($mm->isExist($id)) {
            $this->uninstallModule($id);
        } else {
            $this->stdout("模块({$id})不存在!\n");
        }
    }

    /**
     * 更新所有模块权限
     */
    public function actionUpdatePermissions()
    {
        $this->stdout("正在更新所有模块权限...");
        $this->updatePermissions();
        $this->stdout("完成！\n");
    }

    /**
     * 更新所有模块的权限
     * @throws \yii\base\ErrorException
     */
    protected function updatePermissions()
    {
        $mm = $this->getModuleManager();
        $auth = $this->getAuth();
        $permissions = $mm->getAllPermissions();
        foreach ($permissions as $id => $permission) {
            foreach ($permission as $name => $description) {
                if (!$auth->getPermission($name)) {
                    $p = $auth->createPermission($name);
                    $p->description = $description;
                    if (!$auth->add($p)) {
                        throw new InvalidConfigException('添加权限错误！' . __METHOD__);
                    }
                }
            }
        }
    }

    /**
     * 配置并安装所有模块
     * @throws \yii\base\ErrorException
     */
    protected function installModules()
    {
        $mm = $this->getModuleManager();
        $modules = $mm->getModules();
        $modulesArr = [];
        foreach ($modules as $id => $m) {
            $this->stdout("\n..检测到模块({$id})");
            if ($mm->hasMigration($id)) {
                $this->installModule($id);
            }
            $modulesArr[$id] = [
                'class' => $m['class'],
                'bootstrap' => $m['bootstrap'],
            ];
        }
        $content = serialize($modulesArr);
        $this->stdout("正在生成模块配置文件@app/config/modules.php ...");
        file_put_contents(\Yii::getAlias('@app/config/modules.php'), $content);
        $this->stdout("完成！\n");
    }

    /**
     * 安装指定模块, 执行migrations操作.
     * @param $id string 指定模块id
     */
    protected function installModule($id)
    {
        $mm = $this->getModuleManager();
        $this->stdout("\n....正在安装模块({$id}) ...");
        $migrationPath = $mm->getMigrationPath($id);
        $migrationTable = $mm->getMigrationTableName($id);
        $cmd = "php yii migrate/up --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
        system($cmd);
        $this->stdout("....模块{$id}安装完成!\n");
    }

    /**
     * 卸载指定模块, 执行migrations清除操作
     * @param $id string 指定模块的id
     */
    protected function uninstallModule($id)
    {
        $mm = $this->getModuleManager();
        $this->stdout("正在卸载模块{$id} ...");
        if ($this->getModuleManager()->hasMigration($id)) {
            $migrationPath = $mm->getMigrationPath($id);
            $migrationTable = $mm->getMigrationTableName($id);
            $cmd = "php yii migrate/to 0 --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
            system($cmd);
        }
        $this->stdout("完成！\n");
        if ($this->confirm("是否删除该模块目录？")) {
            $path = $this->getModuleManager()->getPath($id);
            FileHelper::removeDirectory($path);
            $this->stdout("已删除`{$id}`模块目录{$path}！", Console::FG_GREEN);
        } else {
            $this->stdout("未删除`{$id}`模块目录，请手动删除!\n", Console::FG_YELLOW);
        }
    }

    /**
     * 获取模块管理组件
     * @return \app\modules\ModuleManager
     */
    protected function getModuleManager()
    {
        return \Yii::$app->moduleManager;
    }

    /**
     * 获取权限管理组件
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }

}
