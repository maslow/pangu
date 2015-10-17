<?php

namespace app\modules\man\models;

use app\modules\man\Module;
use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package app\modules\man\models
 */
class UpdateForm extends Model
{
    public $id;
    public $username;
    public $password;
    public $password_confirm;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','username','role'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            ['role', 'string', 'max' => 32],
            [['username'], 'exist', 'targetClass' => Manager::className()],
            [['id'], 'exist', 'targetClass' => Manager::className()],
            ['password_confirm','compare','compareAttribute'=>'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'password_confirm' => '重复密码',
            'role' => '角色',
        ];
    }

    /**
     * 验证表单，并执行保存操作
     * @return bool
     */
    public function update()
    {
        if ($this->validate()) {
            /* @var Manager $manager */
            $manager = Manager::findOne($this->id);
            try {
                if($this->password){
                    $manager->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                }
                $manager->updated_at = time();

                if($manager->save()){
                    $role = $this->getAuth()->getRole($this->role);
                    $this->getAuth()->revokeAll($manager->id);
                    $this->getAuth()->assign($role,$manager->id);
                    return true;
                }
            } catch (\Exception $e) {
                $this->addError('username', '该用户异常!');
                return false;
            }
        }
        return false;
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth(){
        return Yii::$app->authManager;
    }
}
