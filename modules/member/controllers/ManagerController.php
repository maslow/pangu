<?php

namespace app\modules\member\controllers;

use app\base\CheckAccessTrait;
use app\base\Event;
use app\modules\member\models\CreateUserForm;
use app\modules\member\models\UpdateUserForm;
use app\modules\member\Module;
use app\modules\member\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManagerController implements the CRUD actions for User model.
 */
class ManagerController extends Controller
{
    use CheckAccessTrait;

    public $layout = '/manager';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     * @permission member.users.list
     */
    public function actionIndex()
    {
        if (!$this->checkAccess('member.users.list')) {
            return $this->goNotAllowed();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @permission member.users.view
     */
    public function actionView($id)
    {
        if (!$this->checkAccess('member.users.view')) {
            return $this->goNotAllowed();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @permission member.users.create
     */
    public function actionCreate()
    {
        if (!$this->checkAccess('member.users.create')) {
            return $this->goNotAllowed();
        }

        Event::trigger(Module::className(), Module::EVENT_BEFORE_CREATE_USER);

        $model = new CreateUserForm();
        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @permission member.users.update
     */
    public function actionUpdate($id)
    {
        if (!$this->checkAccess('member.users.update')) {
            return $this->goNotAllowed();
        }

        Event::trigger(Module::className(), Module::EVENT_BEFORE_UPDATE_USER);

        $user = $this->findModel($id);
        $model = new UpdateUserForm();

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            return $this->redirect(['index']);
        } else {
            $model->username = $user->username;
            return $this->render('update', [
                'model' => $model,
                'user' => $user
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @permission member.users.delete
     */
    public function actionDelete($id)
    {
        if (!$this->checkAccess('member.users.delete')) {
            return $this->goNotAllowed();
        }

        Event::trigger(Module::className(), Module::EVENT_BEFORE_DELETE_USER);
        $user = $this->findModel($id);
        if ($user->delete()) {
            Event::trigger(Module::className(), Module::EVENT_DELETE_USER_SUCCESS, new Event(['model' => $user]));
        } else {
            Event::trigger(Module::className(), Module::EVENT_DELETE_USER_FAIL, new Event(['model' => $user]));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}