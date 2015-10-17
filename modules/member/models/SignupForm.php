<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/12
 * Time: 上午12:39
 */

namespace app\modules\member\models;


use yii\base\ErrorException;
use yii\base\Model;

/**
 * Class SignupForm
 * @package app\modules\member\models
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $password_confirm;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username','password'],'string','max'=>32,'min'=>3],
            ['username','unique','targetClass'=>User::className()],
            ['password_confirm','compare','compareAttribute'=>'password']
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'password_confirm'=>'重复密码',
        ];
    }

    /**
     * 验证注册信息并保存入库
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function signup(){
        if($this->validate()){
            $user = new User();
            $user->username = $this->username;
            $user->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->updated_at = time();
            $user->created_at = time();
            try {
                if ($user->save()) {
                    return true;
                }else{
                    \Yii::error(var_export($user->getFirstErrors(),true),__METHOD__);
                    throw new ErrorException('写入数据异常!');
                }
            }catch (\Exception $e){
                $this->addError('username','系统异常!');
                return false;
            }
        }else{
            return false;
        }
    }
}