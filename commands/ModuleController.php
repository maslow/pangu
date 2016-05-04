<?php

namespace app\commands;

use Faker\Provider\File;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Class ModuleController
 * ModuleController 提供了3个模块管理的命令：
 *
 * ```commands
 * #安装、配置、更新模块
 * php yii module/update
 *
 * #flush与update命令相对应，该命令清除所有模块的Migrations（如果存在的话），并不删除模块目录
 * php yii module/flush
 *
 * #uninstall用于卸载单个模块
 * php yii module/uninstall  [module_name]
 *
 * ```
 * @package app\commands
 */
class ModuleController extends Controller
{
    /**
     * 更新所有模块信息，适用于安装模块、卸载模块后、配置模块、更新角色系统权限使用。
     */
    public function actionUpdate()
    {
        $this->stdout("=====> Updating configuration of all modules", Console::FG_BLUE);
        $this->installModules();
        $this->stdout("\n<====== Successful!\n", Console::FG_BLUE);

        $this->updatePermissions();
    }

    /**
     * 清除所有模块数据迁移操作，但并不删除模块目录
     * @throws \yii\base\ErrorException
     */
    public function actionFlush()
    {
        $this->interactive = 0;
        $modules = $this->getModuleManager()->getModules();
        $ordered = array_reverse($modules);
        foreach ($ordered as $id => $m) {
            $this->uninstallModule($id);
        }
    }

    /**
     * 卸载指定模块命令
     * @param $id string 指定模块的id
     */
    public function actionUninstall($id)
    {
        $this->uninstallModule($id);
    }

    /**
     * 创建一个新模块
     * @param $id string 所创建模块的id
     */
    public function actionCreate($id){

        if($this->getModuleManager()->isExist($id)){
            $this->stderr("ERR: {$id} is already exist!\n");
            return ;
        }
        // Create the root directory of module {$id}
        FileHelper::createDirectory($this->getModuleRootPathById($id));

        $this->createModuleMetaFile($id);

        $this->createModuleFile($id);

        $this->createModuleI18nFile($id);

        $this->createModuleMessagesFile($id);

        $this->createModuleMigrationsFile($id);

        $this->createModuleReadMeFile($id);

        $this->createModuleModelsFile($id);

        $this->createModuleViewsFile($id);

        $this->createModuleControllersFile($id);

        $this->createModuleBackendFile($id);

    }

    protected function getModuleRootPathById($id){
        return \Yii::getAlias($this->getModuleManager()->moduleRoot) . "/{$id}";
    }

    protected function createModuleMetaFile($id){
        $templateString = <<<STR
<?php

return [
    'version' => '1.0',
    'description' => 'Description of {$id} module.',

    'bootstrap' => false,
    'backend' => [
        'menu' => [
            'label' => Yii::t('{$id}', 'Stuffs'),
            'url' => ['/{$id}/backend.stuff/index'],
            'items' => [
                [
                    'label' => Yii::t('{$id}', 'Stuff List'),
                    'url' => ['/{$id}/backend.stuff/index'],
                    'permission' => [
                        '{$id}.stuff.list',
                        '{$id}.stuff.update',
                        '{$id}.stuff.delete',
                    ],
                ],
                [
                    'label' => Yii::t('{$id}', 'Create Stuff'),
                    'url' => ['/{$id}/backend.stuff/create'],
                    'permission' => '{$id}.stuff.create'
                ],
            ],
        ],
        'permissions' => [
            '{$id}.stuff.create' => '添加东西',
            '{$id}.stuff.list' => '浏览东西',
            '{$id}.stuff.update' => '更新东西',
            '{$id}.stuff.delete' => '删除东西',
            '{$id}.stuff.view' => '查看东西',
        ],
    ],
    'deps' => [
        'backend',
    ]
];
STR;
        file_put_contents($this->getModuleRootPathById($id)."/meta.php",$templateString);
    }

    protected function createModuleFile($id){
        $templateString = <<<STR
<?php

namespace app\modules\\{$id};

class Module extends \yii\\base\Module
{
    public \$controllerNamespace = 'app\modules\\{$id}\controllers';

    public \$controllerMap = [
        'backend.stuff' => 'app\modules\\{$id}\\backend\StuffController',
    ];

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
STR;
        $moduleFileName = $this->getModuleManager()->defaultClassName.".php";
        file_put_contents($this->getModuleRootPathById($id)."/{$moduleFileName}",$templateString);
    }

    protected function createModuleI18nFile($id){
        $templateString = <<<STR
<?php

return [
    '{$id}' => [
        'class' => 'yii\i18n\PhpMessageSource',
        'basePath' => '@app/modules/{$id}/messages',
        'fileMap' => [
            '{$id}' => '{$id}.php',
        ],
    ],
];
STR;
        file_put_contents($this->getModuleRootPathById($id)."/i18n.php",$templateString);
    }

    protected function createModuleMessagesFile($id){
        $templateString = <<<STR
<?php

return [
    'Stuffs' => '东西管理',
    'Stuff List' => '东西列表',
    'Create Stuff' => '创建东西',
];
STR;
        $path = $this->getModuleRootPathById($id)."/messages";
        FileHelper::createDirectory($path);
        FileHelper::createDirectory($path."/zh-CN");
        file_put_contents("{$path}/zh-CN/{$id}.php",$templateString);
    }

    protected function createModuleReadMeFile($id){
        $templateString = <<<STR
{$id} Module for Pangu
========================

{$id}模块是一个由Pangu自由生成的模块模板。

说明
====

基础信息
-------
    @ 标识 : {$id}
    @ 全局引导 : 否
    @ 必要模块 : 否
    @ 数据迁移 : 是

导出菜单
-------
    * 东西管理
        > 东西列表
        > 创建东西

导出权限
-------
    * {$id}.stuff.create   > 添加东西
    * {$id}.stuff.list     > 浏览东西
    * {$id}.stuff.update   > 更新东西
    * {$id}.stuff.delete   > 删除东西
    * {$id}.stuff.view     > 查看东西

依赖模块
-------
    * backend

安装
---
    1. 将模块目录拷贝到@app/modules目录下

    2. 在项目根目录运行指令
        ```
            php yii module/update
        ```

STR;
        file_put_contents($this->getModuleRootPathById($id)."/README.MD",$templateString);
    }

    protected function createModuleModelsFile($id){
        FileHelper::createDirectory($this->getModuleRootPathById($id)."/models");
    }

     protected function createModuleViewsFile($id){
         FileHelper::createDirectory($this->getModuleRootPathById($id)."/views");
    }

     protected function createModuleControllersFile($id){
         FileHelper::createDirectory($this->getModuleRootPathById($id)."/controllers");
    }

     protected function createModuleBackendFile($id){
         FileHelper::createDirectory($this->getModuleRootPathById($id)."/backend");
    }

    protected function createModuleMigrationsFile($id)
    {
        FileHelper::createDirectory($this->getModuleRootPathById($id)."/migrations");
    }


    /**
     * 更新所有模块的权限
     * @throws \yii\base\ErrorException
     */
    protected function updatePermissions()
    {
        $this->stdout("Updating permissions of all modules ...");

        $mm = $this->getModuleManager();
        $auth = $this->getAuth();
        $permissions = $mm->getAllPermissions();
        foreach ($permissions as $id => $permission) {
            foreach ($permission as $name => $description) {
                if (!$auth->getPermission($name)) {
                    $p = $auth->createPermission($name);
                    $p->description = $description;
                    if (!$auth->add($p)) {
                        throw new InvalidConfigException('Error in adding permission to auth system！' . __METHOD__);
                    }
                }
            }
        }
        $this->stdout("done！\n", Console::FG_GREEN);
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
            $this->stdout("\n---Module named `{$id}` has been found!");
            if ($mm->hasMigration($id)) {
                $this->installModule($id);
            }
            $modulesArr[$id] = [
                'class' => $m['class'],
                'bootstrap' => isset($m['bootstrap']) ? $m['bootstrap'] : false,
                'i18n' => $m['i18n'],
            ];
        }
        $content = serialize($modulesArr);
        $this->stdout("\nGenerating the configuration file of modules : `@app/config/modules.php` ...");
        file_put_contents(\Yii::getAlias('@app/config/modules.php'), $content);
        $this->stdout("done!\n", Console::FG_GREEN);
    }

    /**
     * 安装指定模块, 执行migrations操作.
     * @param $id string 指定模块id
     */
    protected function installModule($id)
    {
        $mm = $this->getModuleManager();
        $this->stdout("\n-----Installing the module `{$id}` ...");
        $migrationPath = $mm->getMigrationPath($id);
        $migrationTable = $mm->getMigrationTableName($id);
        $cmd = "php yii migrate/up --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
        system($cmd);
        $this->stdout("-----The installation of the module `{$id}` has completed!\n", Console::FG_GREEN);
    }

    /**
     * 卸载指定模块, 执行migrations清除操作
     * @param $id string 指定模块的id
     * @since LaoJiu 2015-11-21 add delete permissions code
     */
    protected function uninstallModule($id)
    {
        $mm = $this->getModuleManager();
        if (!$mm->isExist($id)) {
            $this->stderr("Error: the module which id equals ({$id}) is not exist!\n", Console::FG_RED);
        }
        $this->stdout("Uninstalling the module `{$id}` ...\n");

        //delete permissions
        $manager = \Yii::$app->getAuthManager();
        $permissions = $mm->getPermissions($id);
        if ($permissions) {
            foreach ($permissions as $permission=>$name) {
                $child = $manager->getPermission($permission);
                try {
                    $manager->remove($child);
                } catch (\Exception $exc) {
                    $this->stdout("delete permission `{$permission}` fail ...\n");
                }
                $this->stdout("delete permission `{$permission}` Successful ...\n");
            }
        }
        if ($this->getModuleManager()->hasMigration($id)) {
            $migrationPath = $mm->getMigrationPath($id);
            $migrationTable = $mm->getMigrationTableName($id);
            $cmd = "php yii migrate/to 0 --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
            system($cmd);
        }
        $this->stdout("done!\n", Console::FG_GREEN);
        if ($this->interactive) {
            $path = $this->getModuleManager()->getPath($id);
            if ($this->confirm("Do you want to delete the directory of the module `{$id}`?")) {
                FileHelper::removeDirectory($path);
                $this->stdout("The module `{$id}` has been deleted!", Console::FG_GREEN);
            } else {
                $this->stdout("The directory of the module `{$id}` has been reserved ，you can delete it later! ({$path})\n", Console::FG_YELLOW);
            }
        }
    }

    /**
     * 获取模块管理组件
     * @return \app\base\ModuleManager
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
