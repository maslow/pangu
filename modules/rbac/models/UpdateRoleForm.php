<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/16
 * Time: 下午8:39
 */

namespace app\modules\rbac\models;


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
            [['name', 'description','permissions'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 16],
            ['description', 'string', 'min' => 3, 'max' => 16],
            ['data', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'ID',
            'description' => "名称",
            'data' => "备注",
            'permissions' =>'权限'
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
            // TODO 判断$role是否合法
            $role->description = $this->description;
            $role->data = $this->data;

            $this->getAuth()->removeChildren($role);
            foreach($this->permissions as $name){
                $p = $this->getAuth()->getPermission($name);
                $this->getAuth()->addChild($role,$p);
            }
            $this->getAuth()->update($role->name,$role);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 莸取authManager组件
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth(){
        return \Yii::$app->authManager;
    }
}