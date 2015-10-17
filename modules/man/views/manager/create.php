<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/17
 * Time: 下午8:01
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $model \app\modules\man\models\CreateForm */

$this->title = "创建管理员";
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <hr/>
<?php $form = ActiveForm::begin([
    'id' => 'create-manager',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-sm-1 control-label'],
    ]
]); ?>
<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_confirm')->passwordInput() ?>

<?php
$roles = Yii::$app->authManager->getRoles();
$roleList = [];
foreach ($roles as $role) {
    $roleList[$role->name] = $role->description;
}
?>
<?= $form->field($model, 'role')->radioList($roleList) ?>
    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
            <?= Html::submitButton('创建', ['class' => 'btn btn-primary', 'name' => 'create-manager']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>