<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\member\models\LoginForm */
/* @var $form ActiveForm */
$this->title = Yii::t('member', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="default-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill the form to login.</p>
    <hr/>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('member', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <hr/>
    <a href="<?=\yii\helpers\Url::to(['/site/auth','authclient'=>'qq'])?>"><?=Yii::t('member','QQ')?></a>
    <a href="<?=\yii\helpers\Url::to(['/site/auth','authclient'=>'wx'])?>"><?=Yii::t('member','WeChat')?></a>
    <a href="<?=\yii\helpers\Url::to(['/site/auth','authclient'=>'wb'])?>"><?=Yii::t('member','Weibo')?></a>

</div><!-- default-login -->
