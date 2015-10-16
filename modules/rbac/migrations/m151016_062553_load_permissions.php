<?php

use yii\db\Schema;
use yii\db\Migration;

class m151016_062553_load_permissions extends Migration
{

    public function up()
    {
        $auth = Yii::$app->authManager;
        $permissions = $this->loadPermissions();
        foreach($permissions as $module_id => $module_permission){
            foreach($module_permission as $permission => $title){
                $per = $auth->createPermission($permission);
                $per->description = $title;
                if(!$auth->add($per)){
                    throw new \yii\base\InvalidConfigException('添加权限错误！'.__METHOD__);
                }
            }
        }
    }

    public function down()
    {
        return true;
    }

    /**
     * 加载所有权限
     * @return array
     * @throws \yii\base\ErrorException
     */
    protected function loadPermissions(){
        $mm = $this->getModuleManager();
        $modules = $mm->getModules();
        $permissions =[];
        foreach($modules as $id=>$m){
            if(isset($m['man']['permissions'])){
                $permissions[$id] = $m['man']['permissions'];
            }
        }
        return $permissions;
    }

    /**
     * 返回模块管理组件
     * @return \app\modules\ModuleManager
     */
    protected function getModuleManager(){
        return Yii::$app->moduleManager;
    }
}
