<?php

namespace app\commands;

use yii\console\Controller;


class InstallController extends Controller
{
    public function actionIndex($env = 'dev')
    {
        $configPath = \Yii::getAlias('@app/config');
        $dbExample = $configPath . '/db-example.php';
        $dbPath = $configPath . '/db.php';
        $devLock = $configPath . '/dev.lock';

        echo "Clear Env...";
        file_exists($dbPath) ? unlink($dbPath) : print('x..');
        file_exists($devLock) ? unlink($devLock) : print('x..');
        echo "Done!\n";


        file_put_contents($dbPath, file_get_contents($dbExample)) ? print($dbPath . "\n") : print("error!");
        if ($env == 'dev') {
            file_put_contents($devLock, 'Development Env') ? print($devLock . "\n") : print("error!");
        }
    }
}
