<?php

namespace app\modules\rbac\controllers;

use app\modules\rbac\models\CreateRoleForm;
use app\modules\rbac\models\UpdateRoleForm;
use yii\web\NotFoundHttpException;

class ManagerController extends \yii\web\Controller
{
    public $layout = 'manager';


    /**
     * 显示角色列表
     * @return string
     * @permission rbac.roles.list
     */
    public function actionRoles()
    {
        if(!$this->checkAccess('rbac.roles.list')){
            return $this->goNotAllowed();
        }

        return $this->render('roles', ['roles' => $this->getAuth()->getRoles()]);
    }

    /**
     * 新建一个角色
     * @return string|\yii\web\Response
     * @permission rbac.roles.create
     */
    public function actionCreateRole()
    {
        if(!$this->checkAccess('rbac.roles.create')){
            return $this->goNotAllowed();
        }

        $model = new CreateRoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->create()) {
            $this->sendFlashMessage("创建角色成功！");
            return $this->redirect(['roles']);
        }
        return $this->render('create-role', ['model' => $model]);
    }

    /**
     * 更新指定角色
     * @param $name string 指定角色的name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @permission rbac.roles.update
     */
    public function actionUpdateRole($name)
    {
        if(!$this->checkAccess('rbac.roles.update')){
            return $this->goNotAllowed();
        }

        $role = $this->getAuth()->getRole($name);
        if(!$role){
            throw new NotFoundHttpException("不存在名称为{$name}的角色!");
        }

        $model = new UpdateRoleForm();

        if ($model->load(\Yii::$app->request->post()) && $model->update()) {
            $this->sendFlashMessage("更新角色成功！");
            return $this->redirect(['roles']);
        }

        if (!\Yii::$app->request->isPost) {
            $model->name = $role->name;
            $model->description = $role->description;
            $model->data = $role->data;
        }

        // 生成权限列表数组，供checkboxList做为参数使用
        $permissionObjects = $this->getAuth()->getPermissions();
        $permissions = [];
        foreach ($permissionObjects as $permission) {
            $permissions[$permission->name] = $permission->description;
            // 如果是Get请求，生成$model->permissions的值
            if (\Yii::$app->request->isGet && $this->getAuth()->hasChild($role, $permission)) {
                $model->permissions[] = $permission->name;
            }
        }
        return $this->render('update-role', ['model' => $model, 'permissions' => $permissions]);
    }

    /**
     * 删除指定角色
     * @param $name string 指定角色标识
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @permission rbac.roles.delete
     */
    public function actionDeleteRole($name)
    {
        if(!$this->checkAccess('rbac.roles.delete')){
            return $this->goNotAllowed();
        }

        $role = $this->getAuth()->getRole($name);
        if(!$role){
            throw new NotFoundHttpException("要删除的角色不存在！");
        }
        if($this->getAuth()->remove($role)){
            $this->sendFlashMessage("删除角色成功！");
        }else{
            $this->sendFlashMessage("删除角色失败！");
        }
        return $this->redirect(['roles']);
    }

    /**
     * 返回应用权限管理组件对象
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
            $this->sendFlashMessage("您没有进行该操作的权限({$permission})!");
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

    /**
     * 发送通知信息到下一个请求页面
     * @param $message string 要发送的信息
     */
    protected function sendFlashMessage($message){
        \Yii::$app->session->setFlash(\Yii::$app->params['flashMessageParam'], $message);
    }
}
