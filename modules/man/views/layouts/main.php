<?php
use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
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
        <div class="container">
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; vip-design.net <?= date('Y') ?></p>
            <p class="pull-right"> Powerred by EY2B</p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
