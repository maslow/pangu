<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\member\models\User */

$this->title = '编辑用户: ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑用户  ';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?>: TBD</h1>

</div>
