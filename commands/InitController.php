<?php

namespace app\commands;

use yii\console\Controller;
use yii\rbac\Role;

class InitController extends Controller
{
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

        echo "Clear Env...";
        file_exists($dbFile) ? unlink($dbFile) : print('x..');
        file_exists($index) ? unlink($index) : print('x..');
        echo "Done!\n";

        file_put_contents($dbFile, file_get_contents($dbExample)) ? print($dbFile . "\n") : print("error!");
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
    public function actionAdmin($user_id = 1){

        $auth = \Yii::$app->authManager;
        $role = new Role();
        $role->name = 'super-admin';
        $role->description = '超级管理员';

        $this->stdout("..创建角色:{$role->description},正在初始化角色权限 ");
        $auth->add($role);

        $permissions = $auth->getPermissions();
        foreach($permissions as $p){
            $auth->addChild($role,$p);
            $this->stdout('.');
        }
        $this->stdout("完成！\n");

        $this->stdout("..初始化用户ID:{{$user_id}}为{$role->description}.\n");
        $auth->assign($role,$user_id);
        $this->stdout("..完成！\n");
    }
}
