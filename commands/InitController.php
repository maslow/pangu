<?php

namespace app\commands;

use yii\console\Controller;


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
        $configPath = \Yii::getAlias('@app/config');
        $dbExample = $configPath . '/db-example.php';
        $dbPath = $configPath . '/db.php';

        $webroot = \Yii::getAlias('@app/web');
        $index = $webroot . '/index.php';

        $envRoot = \Yii::getAlias('@app/environments');
        $indexDev = $envRoot . '/index-dev.php';
        $indexProd = $envRoot . '/index-prod.php';

        echo "Clear Env...";
        file_exists($dbPath) ? unlink($dbPath) : print('x..');
        file_exists($index) ? unlink($index) : print('x..');
        echo "Done!\n";

        file_put_contents($dbPath, file_get_contents($dbExample)) ? print($dbPath . "\n") : print("error!");
        if ($env == 'dev') {
            file_put_contents($index, file_get_contents($indexDev)) ? print($index . " to Dev Evn. \n") : print('error!');
        } else {
            file_put_contents($index, file_get_contents($indexProd)) ? print($index . " to Prod Evn. \n") : print('error!');
        }
    }
}