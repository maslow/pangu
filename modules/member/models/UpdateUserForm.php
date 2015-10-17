<?php

namespace app\modules\member\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;


/**
 * Class UpdateUserForm
 * @package app\modules\man\models
 */
class UpdateUserForm extends Model
{
    public $username;
    public $password;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'password_confirm'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            [['username'], 'exist', 'targetClass' => User::className()],
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
        ];
    }

    /**
     * 验证表单，并执行保存操作
     * @return bool
     */
    public function update()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = User::findOne(['username'=>$this->username]);
            try {
                if($this->password){
                    $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                }
                $user->updated_at = time();
                $user->auth_key = Yii::$app->security->generateRandomString();

                if ($user->save()) {
                    return true;
                }else{
                    throw new InvalidParamException();
                }
            } catch (\Exception $e) {
                Yii::error($user->getErrors());
                $this->addError('username', '写入数据异常!');
                return false;
            }
        }
        return false;
    }
}
