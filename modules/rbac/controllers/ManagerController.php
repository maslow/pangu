<?php

namespace app\modules\rbac\controllers;

use app\modules\rbac\models\CreateRoleForm;

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
        return $this->render('roles',['roles'=>$this->getAuth()->getRoles()]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreateRole(){
        $model = new CreateRoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->create()){
            return $this->redirect(['roles']);
        }
        return $this->render('create-role',['model'=>$model]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDeleteRole($id){
        return $this->redirect(['roles']);
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth(){
        return \Yii::$app->authManager;
    }
}
