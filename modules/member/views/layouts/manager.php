<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

\yii\bootstrap\BootstrapAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        if(Yii::$app->session->hasFlash(Yii::$app->params['flashMessageParam'])){
            echo \yii\bootstrap\Alert::widget([
                'options'=>[
                    'class'=>'alert-warning',
                ],
                'body'=>Yii::$app->session->getFlash(Yii::$app->params['flashMessageParam']),
            ]);
        }
        ?>
        <?= Breadcrumbs::widget([
            'homeLink'=>['label'=>'用户管理','url'=>\yii\helpers\Url::to('index')],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
