<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\member\models\UpdateUserForm */
/* @var $user app\modules\member\models\User */

$this->title = Yii::t('member', 'Update User');
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id' => 'update-user',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'username', ['options' => ['style' => 'display:none']])->hiddenInput() ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_confirm')->passwordInput() ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(Yii::t('member', 'Update User'), ['class' => 'btn btn-primary', 'name' => 'update-user']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
