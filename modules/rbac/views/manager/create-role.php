<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $model \app\modules\rbac\models\CreateRoleForm */

$this->title = Yii::t('rbac', 'Create Role');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id' => 'create-role',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'description') ?>
        <?= $form->field($model, 'data')->textarea() ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(Yii::t('rbac', 'Create Role'), ['class' => 'btn btn-primary', 'name' => 'create-role']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
