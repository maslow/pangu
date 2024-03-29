<?php

namespace app\modules\backend\models;

use app\modules\backend\Module;
use Yii;
use app\base\Event;
use yii\base\Model;

/**
 * Class UpdateForm
 * @package app\modules\backend\models
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
            [['id', 'username', 'role'], 'required'],
            [['username', 'password'], 'string', 'min' => 3, 'max' => 32],
            ['role', 'string', 'max' => 32],
            [['username'], 'exist', 'targetClass' => Manager::className()],
            [['id'], 'exist', 'targetClass' => Manager::className()],
            ['password_confirm', 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('backend', 'Username'),
            'password' => Yii::t('backend', 'Password'),
            'password_confirm' => Yii::t('backend', 'Confirm Password'),
            'role' => Yii::t('backend', 'Role'),
        ];
    }

    /**
     * 验证表单，并执行保存操作
     * @return bool
     */
    public function update()
    {
        $event = new Event(['model' => $this]);
        if ($this->validate()) {
            /* @var Manager $manager */
            $manager = Manager::findOne($this->id);
            try {
                if ($this->password) {
                    $manager->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                }
                $manager->updated_at = time();

                if ($manager->save()) {
                    $role = $this->getAuth()->getRole($this->role);
                    if (!$role) {
                        throw new \InvalidArgumentException(Yii::t('backend', 'The role is not exist!'));
                    }
                    $this->getAuth()->revokeAll($manager->id);
                    $this->getAuth()->assign($role, $manager->id);
                    Event::trigger(Module::className(), Module::EVENT_UPDATE_MANAGER_SUCCESS, $event);
                    return true;
                } else {
                    Yii::error($this->getErrors());
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage());
                $this->addError('username', Yii::t('backend','Throw an exception of saving data!'));
            }
        }
        Event::trigger(Module::className(), Module::EVENT_UPDATE_MANAGER_FAIL, $event);
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