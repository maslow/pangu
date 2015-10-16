<?php

namespace app\modules\rbac\controllers;

use app\modules\rbac\models\CreateRoleForm;
use app\modules\rbac\models\UpdateRoleForm;

class ManagerController extends \yii\web\Controller
{
    public $layout = 'manager';

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionRoles()
    {
        return $this->render('roles', ['roles' => $this->getAuth()->getRoles()]);
    }

    /**
     * 新建一个角色
     * @return string|\yii\web\Response
     */
    public function actionCreateRole()
    {
        $model = new CreateRoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->create()) {
            return $this->redirect(['roles']);
        }
        return $this->render('create-role', ['model' => $model]);
    }

    /**
     * 更新指定角色
     * @param $name string 指定角色的name
     * @return string|\yii\web\Response
     */
    public function actionUpdateRole($name)
    {
        $role = $this->getAuth()->getRole($name);
        // TODO  判断$role是否合法

        $model = new UpdateRoleForm();

        if ($model->load(\Yii::$app->request->post()) && $model->update()) {
            return $this->redirect(['roles']);
        }

        if (!\Yii::$app->request->isPost) {
            $model->name = $role->name;
            $model->description = $role->name;
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
     * @param $name string
     * @return \yii\web\Response
     */
    public function actionDeleteRole($name)
    {
        return $this->redirect(['roles']);
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }
}
