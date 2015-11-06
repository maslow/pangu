<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/11/6
 * Time: 22:51
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $model \app\modules\man\models\ResetPasswordForm */

$this->title = Yii::t('man', 'Reset Password');
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>
    <hr/>
<?php $form = ActiveForm::begin([
    'id' => 'reset-password',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-sm-1 control-label'],
    ]
]); ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_confirm')->passwordInput() ?>
    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
            <?= Html::submitButton(Yii::t('man', 'Reset Password'), ['class' => 'btn btn-primary', 'name' => 'reset-password']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>