<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('man', 'Manager List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <p>
            <?= Html::a(Yii::t('man', 'Create Manager'), ['create'], ['class' => 'btn btn-default']) ?>
        </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'username',
                [
                    'value' => function ($row) {
                        $roles = Yii::$app->authManager->getRolesByUser($row->id);
                        if ($role = current($roles)) {
                            return $role->description;
                        } else {
                            return '-';
                        }
                    },
                    'label' => Yii::t('man', 'Role'),
                ],
                [
                    'attribute' => 'created_by',
                    'value' => function ($row) {
                        /* @var $c \app\modules\man\models\Manager */
                        $c = \app\modules\man\models\Manager::findOne($row->created_by);
                        return $c->username;
                    },
                ],
                'created_ip',
                'created_at:datetime',
                'updated_at:datetime',
                [
                    'class' => '\yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                ],
            ],
        ]); ?>
    </div>
</div>
