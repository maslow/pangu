<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/16
 * Time: 下午8:01
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $model \app\modules\rbac\models\UpdateRoleForm */

$this->title = Yii::t('rbac', 'Update Role');
$this->params['breadcrumbs'][] = $model->description;
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'update-role',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-sm-1 control-label'],
    ]
]); ?>
<?= $form->field($model, 'name',['options'=>['style'=>'display:none;']])->hiddenInput() ?>
<?= $form->field($model, 'description') ?>
<?= $form->field($model, 'data')->textarea() ?>
<?= $form->field($model, 'permissions')->checkboxList($permissions) ?>
    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
            <?= Html::submitButton(Yii::t('rbac', 'Update Role'), ['class' => 'btn btn-primary', 'name' => 'update-role']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>