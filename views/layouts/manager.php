<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

\yii\bootstrap\BootstrapAsset::register($this);
\app\assets\LayerAsset::register($this);
?>
<script>
    <?php $this->beginBlock('prompt_js') ?>
    layer.msg('<?=Yii::$app->session->getFlash(Yii::$app->params['prompt.param.backend'])?>');
    <?php $this->endBlock() ?>
</script>

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
    if (Yii::$app->session->hasFlash(Yii::$app->params['prompt.param.backend'])) {
         $this->registerJs($this->blocks['prompt_js']);
         /*
          * \yii\bootstrap\Alert::widget([
            'options' => [
                'class' => 'alert-warning',
            ],
            'body' => Yii::$app->session->getFlash(Yii::$app->params['prompt.param.backend']),
        ]);
         */
    }
    ?>
    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
