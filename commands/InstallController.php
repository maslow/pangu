<?php

namespace app\commands;

use app\modules\ModuleManager;
use yii\console\Controller;


class InstallController extends Controller
{
    public function actionIndex($env = 'dev')
    {
        $configPath = \Yii::getAlias('@app/config');
        $dbExample = $configPath . '/db-example.php';
        $dbPath = $configPath . '/db.php';

        $webroot = \Yii::getAlias('@app/web');
        $indexDev = $webroot . '/../index-dev.php';
        $indexProd = $webroot . '/../index-prod.php';
        $index = $webroot . '/index.php';

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

        echo "Update the modules' config...";
        $mm = new ModuleManager();
        $modules = $mm->getModules();
        $content = serialize($modules);
        file_put_contents(\Yii::getAlias('@app/config/modules.php'),$content);
        echo "Done!\n";
    }
}
