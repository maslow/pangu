<?php
/* @var $this yii\web\View */
/* @var $roles yii\rbac\Role[] */
use \yii\helpers\Html;
use yii\helpers\Url;

$this->title = '角色管理';
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
            <th>Name</th>
            <th>ID</th>
            <th>Operation</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?= $role->description ?></td>
                <td><?= $role->name ?></td>
                <td><?= Html::a('Update',['manager/update-role','name'=>$role->name],['class'=>'btn']) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
