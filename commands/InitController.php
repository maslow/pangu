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
}
