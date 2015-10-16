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
            'name' => 'ID',
            'description' => "名称",
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
            $role->name = $this->name;
            $role->description = $this->description;
            $role->data = $this->data;
            $this->getAuth()->add($role);
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