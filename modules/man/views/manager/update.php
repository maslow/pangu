<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/17
 * Time: 下午3:01
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $model \app\modules\man\models\UpdateForm */

$this->title = Yii::t('man','Update Manager');
$this->params['breadcrumbs'][] = $model->username;
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>
    <?=Html::encode($this->title)?>
</h1>
<hr/>
<?php $form = ActiveForm::begin([
    'id' => 'update-manager',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-sm-1 control-label'],
    ]
]); ?>
<?= $form->field($model, 'id', ['options' => ['style' => 'display:none;']])->hiddenInput() ?>
<?= $form->field($model, 'username', ['options' => ['style' => 'display:none;']])->hiddenInput() ?>
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
            <?= Html::submitButton(Yii::t('man','Update Manager'), ['class' => 'btn btn-primary', 'name' => 'update-manager']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>