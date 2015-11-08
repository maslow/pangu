<?php

namespace app\commands;

use app\modules\man\models\Manager;
use yii\console\Controller;
use yii\rbac\Role;

class InitController extends Controller
{
    public function init(){
        if(strtoupper(substr(PHP_OS,0,3))==='WIN'){
            exec('chcp 65001');
        }
    }

    /**
     * 系统安装命令
     * ```
     * php yii init
     * #equal to
     * php yii init dev
     * #or
     * php yii init prod
     *
     * ```
     * @param string $env 'dev'或'prod' 分别表示安装系统为开发环境或生产环境
     */
    public function actionIndex($env = 'dev')
    {
        $envRoot = \Yii::getAlias('@app/environments');
        $indexDev = $envRoot . '/index-dev.php';
        $indexProd = $envRoot . '/index-prod.php';
        $dbExample = $envRoot . '/db-example.php';

        $configRoot = \Yii::getAlias('@app/config');
        $dbFile = $configRoot . '/db.php';

        $webroot = \Yii::getAlias('@app/web');
        $index = $webroot . '/index.php';

        echo "清除环境...";
        file_exists($index) ? unlink($index) : print('x..');
        echo "完成!\n";

        if(!file_exists($dbFile)){
            file_put_contents($dbFile, file_get_contents($dbExample)) ? print($dbFile . "\n") : print("error!");
        }

        if ($env == 'dev') {
            file_put_contents($index, file_get_contents($indexDev)) ? print($index . " to Dev Evn. \n") : print('error!');
        } else {
            file_put_contents($index, file_get_contents($indexProd)) ? print($index . " to Prod Evn. \n") : print('error!');
        }
    }

    /**
     * 初始化系统第一个超级管理员，仅在系统初建时使用
     * @param int $user_id
     */
    public function actionAdmin($user_id = 1)
    {

        $auth = \Yii::$app->authManager;

        $role = new Role();
        $role->name = 'super-admin';
        $role->description = 'Super Admin';
        if(!$auth->getRole($role->name)){
            $auth->add($role);
        }else{
            $role = $auth->getRole($role->name);
        }

        $this->stdout("正在创建角色{{$role->description}}并初始化角色权限 ");
        $permissions = $auth->getPermissions();
        foreach ($permissions as $p) {
            if(!$auth->hasChild($role,$p)) {
                $auth->addChild($role, $p);
            }
        }
        $this->stdout("...\n");

        $manager = new Manager();
        $manager->username = $this->prompt("请输入{$role->description}的用户名:",
            ['default'=>'manager']);
        $password = $this->prompt("请输入{$role->description}的密码:",
            ['default'=>'999999']);

        $manager->password_hash = \Yii::$app->security->generatePasswordHash($password);
        $manager->auth_key = \Yii::$app->security->generateRandomString();
        $manager->created_at= time();
        $manager->updated_at = time();
        $manager->created_by = 1; //to be itself
        $manager->created_ip = '0.0.0.0';
        $manager->locked = 0;
        if($manager->save()){
            $this->stdout("\n正在将管理员:{{$manager->username}}提升为{$role->description}角色 ...");
            $auth->removeAllAssignments();
            $auth->assign($role, $manager->id);
            $this->stdout("完成！\n");
        }else{
            $this->stdout("创建管理员失败！错误：".var_export($manager->getErrors(),true)."\n");
            //回滚角色与权限操作
            $this->stdout("正在回滚角色与权限操作 ...");
            $auth->removeAllAssignments();
            $auth->remove($role);
            $this->stdout("完成！\n");
        }
    }
}
