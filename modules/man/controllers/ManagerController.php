<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/17
 * Time: 下午2:33
 */

namespace app\modules\man\controllers;


use app\modules\man\models\Manager;
use app\modules\man\models\UpdateForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ManagerController extends Controller{
    public $layout = "manager";

    /**
     * 显示管理员列表
     * @return string
     */
    public function actionList(){
        $data = new ActiveDataProvider([
            'query'=>Manager::find(),
        ]);
        return $this->render('list',['dataProvider'=>$data]);
    }

    /**
     * 创建管理员
     * @return string
     */
    public function actionCreate(){
        return $this->render('create');
    }

    /**
     * 编辑指定管理员
     * @param $id integer 指定管理员id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id){
        $manager = Manager::findOne($id);
        if(!$manager){
            throw new NotFoundHttpException("管理员:$id 不存在!");
        }

        $model = new UpdateForm();
        if($model->load(\Yii::$app->request->post()) && $model->update()){
            return $this->redirect(['list']);
        }

        if(\Yii::$app->request->isGet){
            $model->id = $id;
            $model->username = $manager->username;
            $roles = $this->getAuth()->getRolesByUser($id);
            if($role = current($roles)){
                $model->role = $role->name;
            }
        }

        return $this->render('update',['model'=>$model]);
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth(){
        return \Yii::$app->authManager;
    }
}
