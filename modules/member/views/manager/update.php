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
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <hr/>
<?php $form = ActiveForm::begin([
    'id' => 'update-user',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-sm-1 control-label'],
    ]
]); ?>
<?= $form->field($model, 'username',['options'=>['style'=>'display:none']])->hiddenInput() ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_confirm')->passwordInput() ?>

    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
            <?= Html::submitButton(Yii::t('member', 'Update User'), ['class' => 'btn btn-primary', 'name' => 'update-user']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>