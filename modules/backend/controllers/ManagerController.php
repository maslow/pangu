<?php

namespace app\modules\backend\controllers;


use app\base\CheckAccessTrait;
use app\modules\backend\models\CreateForm;
use app\modules\backend\models\Manager;
use app\modules\backend\models\ResetPasswordForm;
use app\modules\backend\models\UpdateForm;
use app\modules\backend\Module;
use yii\base\Event;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ManagerController extends Controller
{
    use CheckAccessTrait;
    public $layout = "/manager";

    /**
     * 修改当前管理员密码
     * @return string|\yii\web\Response
     * @permission backend.managers.reset.password
     */
    public function actionResetPassword()
    {

        $model = new ResetPasswordForm();
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }
        return $this->render('reset-password', ['model' => $model]);
    }

    /**
     * 显示管理员列表
     * @return string
     * @permission backend.managers.list
     */
    public function actionList()
    {
        if (!$this->checkAccess('backend.managers.list')) {
            return $this->goNotAllowed();
        }

        $data = new ActiveDataProvider([
            'query' => Manager::find(),
        ]);
        return $this->render('list', ['dataProvider' => $data]);
    }

    /**
     * 创建管理员
     * @return string
     * @permission backend.managers.create
     */
    public function actionCreate()
    {
        if (!$this->checkAccess('backend.managers.create')) {
            return $this->goNotAllowed();
        }

        $model = new CreateForm();
        if ($model->load(\Yii::$app->request->post()) && $model->create()) {
            return $this->redirect(['list']);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * 编辑指定管理员
     * @param $id integer 指定管理员id
     * @return string
     * @throws NotFoundHttpException
     * @permission backend.managers.update
     */
    public function actionUpdate($id)
    {
        if (!$this->checkAccess('backend.managers.update')) {
            return $this->goNotAllowed();
        }

        /** @var Manager $manager */
        $manager = Manager::findOne($id);
        if (!$manager) {
            throw new NotFoundHttpException(\Yii::t('backend', 'The Manager (ID:{id}) is not exist!', ['id' => $id]));
        }

        $model = new UpdateForm();
        if ($model->load(\Yii::$app->request->post()) && $model->update()) {
            return $this->redirect(['list']);
        }

        if (\Yii::$app->request->isGet) {
            $model->id = $id;
            $model->username = $manager->username;
            $roles = $this->getAuth()->getRolesByUser($id);
            if ($role = current($roles)) {
                $model->role = $role->name;
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * 删除指定管理员
     * @param $id integer 指管理员id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @permission backend.managers.delete
     */
    public function actionDelete($id)
    {
        if (!$this->checkAccess('backend.managers.delete')) {
            return $this->goNotAllowed();
        }

        /* @var $manager \app\modules\backend\models\Manager */
        $manager = Manager::findOne($id);
        $event = new DeleteManagerEvent(['manager' => $manager]);

        if (!$manager) {
            throw new NotFoundHttpException(\Yii::t('backend', 'The Manager (ID:{id}) is not exist!', ['id' => $id]));
        }

        if ($manager->delete()) {
            Event::trigger(Module::className(), Module::EVENT_DELETE_MANAGER_SUCCESS, $event);
        } else {
            Event::trigger(Module::className(), Module::EVENT_DELETE_MANAGER_FAIL, $event);
        }

        return $this->redirect(['list']);
    }

    /**
     * 进入中控台后默认页面
     * @return string
     */
    public function actionInfo()
    {
        if ($this->getManager()->isGuest) {
            Event::trigger(Module::className(), Module::EVENT_LOGIN_REQUIRED);
            return $this->redirect(['default/login']);
        }
        return $this->render('info');
    }

    /**
     * 获取管理员(manager)组件对象
     * @return \yii\web\User
     */
    protected function getManager()
    {
        return \Yii::$app->manager;
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }

}

/**
 * Class DeleteManagerEvent
 * @package app\modules\backend\controllers
 */
class DeleteManagerEvent extends Event
{
    public $manager;
}