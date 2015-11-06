<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\member\models\CreateUserForm */

$this->title = Yii::t('member', 'Create User');
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <hr/>
<?php $form = ActiveForm::begin([
    'id' => 'create-user',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-sm-1 control-label'],
    ]
]); ?>
<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_confirm')->passwordInput() ?>


    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
            <?= Html::submitButton(Yii::t('member', 'Create User'), ['class' => 'btn btn-primary', 'name' => 'create-user']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>