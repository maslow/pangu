<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\member\models\SignupForm */
/* @var $form ActiveForm */
$this->title = "注册";
$this->params['breadcrumbs'][] =$this->title;
?>
<div class="default-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill the form to signup.</p>
    <hr/>
    <?php $form = ActiveForm::begin([
        'id'=>'signup-form',
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_confirm')->passwordInput() ?>
    
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('注册', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- default-signup -->
