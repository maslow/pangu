<?php
/* @var $this yii\web\View */
/* @var $roles yii\rbac\Role[] */
use \yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('rbac','Roles');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <a href="<?=Url::to(['manager/create-role'])?>" class="btn btn-default">新建角色</a>
    </div>

    <!-- Table -->
    <table class="table .table-hover">
        <thead>
        <tr>
            <th>角色名</th>
            <th>标识</th>
            <th>权限</th>
            <th width="5%">备注</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?= $role->description ?></td>
                <td><?= $role->name ?></td>
                <td>
                    <?php $ps = Yii::$app->authManager->getPermissionsByRole($role->name);
                    foreach($ps as $p): ?>
                        <span class="label label-default" style="padding:2px;"><?=$p->description?></span>
                    <?php endforeach?>
                </td>
                <td><span class="text-muted"><?= $role->data?$role->data:'-'?></span></td>
                <td>
                    <a href="<?=Url::to(['manager/update-role','name'=>$role->name])?>"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="<?=Url::to(['manager/delete-role','name'=>$role->name])?>" data-confirm="您确定要删除此项吗？" data-method="post" data-pjax="0">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>

                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
