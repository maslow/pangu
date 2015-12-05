<?php

use yii\helpers\Html;
use \app\modules\backend\Module;

/* @var $this yii\web\View */
$this->title = \Yii::t('backend','Welcome!');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="default-info" style="min-height: 400px;">
    <h1><?= Html::encode($this->title)?></h1>
    <hr/>
</div><!-- default-login -->
