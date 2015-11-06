<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午7:40
 */

namespace app\modules;


use yii\base\Component;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;


/**
 * Class ModuleManager
 * @package app\modules
 */
class ModuleManager extends Component
{

    public $moduleRoot = '@app/modules';
    public $moduleNamespace = '\\app\\modules';
    public $defaultClassName = 'Module';


    /**
     * 获取指定模块的i18n配置
     * @param $id
     * @return array
     * @throws InvalidConfigException
     */
    public function getI18n($id){
        $path = $this->moduleRoot.'/'.$id.'/i18n.php';
        $path = \Yii::getAlias($path);
        if(file_exists($path)){
            $i18n = require($path);
            return $i18n;
        }else{
            throw new InvalidConfigException("Not found file : {$path}");
        }
    }

    /**
     * 获取所有模块(meta,描述信息)
     * @return array 所获取的模块列表
     * @throws ErrorException
     */
    public function getModules()
    {
        $modules = array();
        $list = $this->listDir(\Yii::getAlias($this->moduleRoot));
        foreach ($list as $id) {
            $m = $this->getModule($id);
            if ($m == false) throw new ErrorException("module ($id) is not exist");
            $modules[$id] = $m;
        }
        return $modules;
    }

    /**
     * 莸取指定模块(meta，描述信息)
     * @param $id string 指定模块的id
     * @return array|false 模块描述信息; 如果模块不存在，返回false
     */
    public function getModule($id)
    {
        if (!$this->isExist($id)) {
            return false;
        }
        $i18n = $this->getI18n($id);
        foreach($i18n as $id => $config){
            \Yii::$app->i18n->translations[$id] = $config;
        }
        $meta = require($this->getMetaFile($id));
        $meta['class'] = $this->getClass($id);
        $meta['i18n'] = $i18n;
        return $meta;
    }

    /**
     * 判断指定模块是否存在
     * @param $id string 指定模块的id
     * @return bool 返回true，存在；false，不存在
     */
    public function isExist($id)
    {
        if (!$this->getMetaFile($id)) {
            return false;
        }
        return true;
    }

    /**
     * 获取指定模块的类名（包括名字空间）
     * @param $id string 指定模块的id
     * @return string 类名（包括名字空间）
     */
    public function getClass($id)
    {
        return $this->moduleNamespace . '\\' . $id . '\\' . $this->defaultClassName;
    }

    /**
     * 获取指定模块的目录路径
     * @param $id string 指定模块的id
     * @return string|false 该模块目录路径; 如果目录不存在，返回false
     */
    public function getPath($id)
    {
        $path = \Yii::getAlias($this->moduleRoot) . '/' . $id;
        return file_exists($path) ? $path : false;
    }

    /**
     * 莸取指定模块的配置信息文件(meta.php)路径
     * @param $id string 指定模块的id
     * @return string|false 该模块meta文件路径，如果文件不存在，返回false
     */
    public function getMetaFile($id)
    {
        if ($path = $this->getPath($id)) {
            $file = $path . '/meta.php';
            return file_exists($file) ? $file : false;
        }
        return false;
    }


    /**
     * 判断模块是否存在数据迁移
     * @param $id
     * @return boolean
     */
    public function hasMigration($id)
    {
        return file_exists(\Yii::getAlias($this->getMigrationPath($id)));
    }

    /**
     * 获取指定模块(module)数据迁移(migration)表名
     * @param $id string 模块id
     * @return string
     */
    public function getMigrationTableName($id)
    {
        return "{{%migration_{$id}}}";
    }

    /**
     * 获取指定模块(module)数据迁移(migration)目录(别名)
     * @param $id string 模块id
     * @return string
     */
    public function getMigrationPath($id)
    {
        return $this->moduleRoot . "/{$id}/migrations";
    }

    /**
     * 获取所有模块的所有权限
     * @return array
     * @throws ErrorException
     */
    public function getAllPermissions()
    {
        $modules = $this->getModules();
        $permissions = [];
        foreach ($modules as $id => $m) {
            $p = $this->getPermissions($id);
            if($p){
                $permissions [$id] = $p;
            }
        }
        return $permissions;
    }

    /**
     * 获取指定模块的权限
     * @param $id string 指定模块的id
     * @return null|array
     */
    public function getPermissions($id)
    {
        $m = $this->getModule($id);
        return isset($m['manager']['permissions']) ? $m['manager']['permissions'] : null;
    }

    /**
     * 遍历模块根目录，返回所有模块目录名(即模块ID)
     * @param $directory string 模块根目录
     * @return array 所有模块目录名(即模块ID)
     */
    protected function listDir($directory)
    {
        $dirs = array();
        $dir = dir($directory);
        while ($file = $dir->read()) {
            if ((is_dir("$directory/$file")) AND ($file != ".") AND ($file != "..")) {
                array_push($dirs, $file);
            }
        }
        $dir->close();
        return $dirs;
    }
}