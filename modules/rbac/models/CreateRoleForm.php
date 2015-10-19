<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/16
 * Time: 下午8:39
 */

namespace app\modules\rbac\models;


use app\modules\rbac\Module;
use yii\base\Event;
use yii\base\Model;
use yii\rbac\Role;

class CreateRoleForm extends Model
{
    public $name;
    public $description;
    public $data;

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 16],
            ['description', 'string', 'min' => 3, 'max' => 16],
            ['data', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => "标识",
            'description' => "标题",
            'data' => "备注"
        ];
    }

    /**
     * 验证表单并执行角色创建操作
     * @return bool
     */
    public function create()
    {
        if ($this->validate()) {
            $role = new Role();
            // 判断角色名是否已存在
            if (!$this->getAuth()->getRole($this->name)){
                $role->name = $this->name;
                $role->description = $this->description;
                $role->data = $this->data;
                $this->getAuth()->add($role);
                Event::trigger(Module::className(),Module::EVENT_CREATE_ROLE_SUCCESS,new CreateRoleEvent(['model'=>$this]));
                return true;
            }else {
                $this->addError('name', "角色名已存在！");
            }
        }
        Event::trigger(Module::className(),Module::EVENT_CREATE_ROLE_FAIL,new CreateRoleEvent(['model'=>$this]));
        return false;
    }

    /**
     * 莸取authManager组件
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }
}

/**
 * Class CreateRoleEvent
 * @package app\modules\rbac\models
 */
class CreateRoleEvent extends Event
{
    public $model;
}