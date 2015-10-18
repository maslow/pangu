<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/17
 * Time: 下午2:33
 */

namespace app\modules\man\controllers;


use app\modules\man\models\CreateForm;
use app\modules\man\models\Manager;
use app\modules\man\models\UpdateForm;
use app\modules\man\Module;
use yii\base\Event;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ManagerController extends Controller
{
    public $layout = "manager";

    /**
     * 显示管理员列表
     * @return string
     * @permission man.managers.list
     */
    public function actionList()
    {
        if (!$this->checkAccess('man.managers.list')) {
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
     * @permission man.managers.create
     */
    public function actionCreate()
    {
        if (!$this->checkAccess('man.managers.create')) {
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
     * @permission man.managers.update
     */
    public function actionUpdate($id)
    {
        if (!$this->checkAccess('man.managers.update')) {
            return $this->goNotAllowed();
        }

        $manager = Manager::findOne($id);
        if (!$manager) {
            throw new NotFoundHttpException("管理员:$id 不存在!");
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
     * @permission man.managers.delete
     */
    public function actionDelete($id)
    {
        if (!$this->checkAccess('man.managers.delete')) {
            return $this->goNotAllowed();
        }

        /* @var $manager \app\modules\man\models\Manager */
        $manager = Manager::findOne($id);
        $event = new DeleteManagerEvent(['manager' => $manager]);

        if (!$manager) {
            throw new NotFoundHttpException("管理员:$id 不存在!");
        }

        if ($manager->delete()) {
            Event::trigger(Module::className(), Module::EVENT_DELETE_MANAGER_SUCCESS, $event);
        } else {
            Event::trigger(Module::className(), Module::EVENT_DELETE_MANAGER_FAIL, $event);
        }

        return $this->redirect(['list']);
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }

    /**
     * 判断当前登录管理员是否满足指定权限
     * @param $permission string 指定权限名
     * @return boolean
     */
    protected function checkAccess($permission)
    {
        /* @var $manager \yii\web\User */
        $manager = \Yii::$app->manager;

        if (!$manager->can($permission)) {
            $e = new PermissionRequiredEvent();
            $e->permission = $permission;
            Event::trigger(Module::className(), Module::EVENT_PERMISSION_REQUIRED, $e);
            return false;
        }

        return true;
    }

    /**
     * 用户无权限访问请求时，跳转到指定页面
     * @return \yii\web\Response
     */
    protected function goNotAllowed()
    {
        return $this->redirect(\Yii::$app->params['route.not.allowed']);
    }


}

/**
 * Class PermissionRequiredEvent
 * @package app\modules\man\controllers
 */
class PermissionRequiredEvent extends Event
{
    public $permission;
}

/**
 * Class DeleteManagerEvent
 * @package app\modules\man\controllers
 */
class DeleteManagerEvent extends Event
{
    public $manager;
}