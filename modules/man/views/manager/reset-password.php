<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $model \app\modules\man\models\ResetPasswordForm */

$this->title = Yii::t('man', 'Reset Password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id' => 'reset-password',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_confirm')->passwordInput() ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(Yii::t('man', 'Reset Password'), ['class' => 'btn btn-primary', 'name' => 'reset-password']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
