<?php

namespace app\modules\man\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * Class CreateForm
 * @package app\modules\man\models
 */
class CreateForm extends Model
{
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
            [['username', 'role', 'password', 'password_confirm'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            ['role', 'string', 'max' => 32],
            [['username'], 'unique', 'targetClass' => Manager::className()],
            ['password_confirm', 'compare', 'compareAttribute' => 'password']
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
    public function create()
    {
        if ($this->validate()) {
            $manager = new Manager();
            try {
                $manager->username = $this->username;
                $manager->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $manager->updated_at = time();
                $manager->created_at = time();
                $manager->auth_key = Yii::$app->security->generateRandomString();
                $manager->created_ip = Yii::$app->request->getUserIP();
                $manager->created_by = Yii::$app->manager->identity->id;
                $manager->locked = 0;

                if ($manager->save()) {
                    $role = $this->getAuth()->getRole($this->role);
                    $this->getAuth()->assign($role, $manager->id);
                    return true;
                }else{
                    throw new InvalidParamException();
                }
            } catch (\Exception $e) {
                Yii::error($manager->getErrors());
                $this->addError('username', '写入数据异常!');
                return false;
            }
        }
        return false;
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return Yii::$app->authManager;
    }
}
