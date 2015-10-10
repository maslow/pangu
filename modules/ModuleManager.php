<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午7:40
 */

namespace app\modules;


use yii\base\Component;
use yii\base\Object;

class Module extends Object{
    public $id;
    public $version;
    public $description;

    public $admin_route;
    public $bootstrap;
    public $deps;

    public $class;

    public function load($meta){
        $this->id = $meta['id'];
        $this->version = $meta['version'];
        $this->description = $meta['description'];
        $this->admin_route = $meta['admin_route'];
        $this->bootstrap = $meta['bootstrap'];
        $this->deps = $meta['deps'];
        $this->class = 'app\\modules\\'.$this->id.'\\Module';
    }
}

class ModuleManager extends Component
{

    public $moduleRoot = '@app/modules';
    public $defaultClassName = 'Module';

    public function getModules()
    {
        $modules = array();
        $list = self::listDir(\Yii::getAlias($this->moduleRoot));
        foreach($list as $id){
            $m = $this->getModule($id);
            array_push($modules,$m);
        }
        return $modules;
    }

    public function getModule($id)
    {
        $meta = require($this->getMetaFile($id));
        $m = new Module();
        $m->load($meta);
        return $m;
    }

    public function isExist($id){
        return true;
    }

    protected function getPath($id){
        return \Yii::getAlias($this->moduleRoot).'/'.$id;
    }

    protected function getMetaFile($id){
        return $this->getPath($id).'/meta.php';
    }

    protected static  function listDir($directory)
    {
        $dirs = array();
        $dir = dir($directory);
        while ($file = $dir->read()) {
            if ((is_dir("$directory/$file")) AND ($file != ".") AND ($file != "..")) {
                array_push($dirs,$file);
            }
        }
        $dir->close();
        return $dirs;
    }
}