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

class UpdateRoleForm extends Model
{
    public $name;
    public $description;
    public $data;
    public $permissions = [];


    public function rules()
    {
        return [
            [['name', 'description', 'permissions'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 16],
            ['description', 'string', 'min' => 3, 'max' => 16],
            ['data', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('rbac', 'Role ID'),
            'description' => \Yii::t('rbac', 'Title'),
            'data' => \Yii::t('rbac', 'Remark'),
            'permissions' => \Yii::t('rbac', 'Permissions'),
        ];
    }

    /**
     * 验证表单并执行角色更新操作
     * @return bool
     */
    public function update()
    {
        if ($this->validate()) {
            $role = $this->getAuth()->getRole($this->name);
            if($role){
                $role->description = $this->description;
                $role->data = $this->data;

                $this->getAuth()->removeChildren($role);
                foreach ($this->permissions as $name) {
                    $p = $this->getAuth()->getPermission($name);
                    $this->getAuth()->addChild($role, $p);
                }
                $this->getAuth()->update($role->name, $role);
                Event::trigger(Module::className(),Module::EVENT_UPDATE_ROLE_SUCCESS,new UpdateRoleEvent(['model'=>$this]));
                return true;
            }else{
                $this->addError('name',\Yii::t('rbac', 'The role is not exist!'));
            }
        }
        Event::trigger(Module::className(),Module::EVENT_UPDATE_ROLE_FAIL,new UpdateRoleEvent(['model'=>$this]));
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
 * Class UpdateRoleEvent
 * @package app\modules\rbac\models
 */
class UpdateRoleEvent extends Event
{
    public $model;
}