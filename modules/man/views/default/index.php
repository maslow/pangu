<?php

/* @var $this \yii\web\View */
/* @var $menu array */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $manager \yii\web\User */
$manager = Yii::$app->manager;
?>

<?php
NavBar::begin([
    'brandLabel' => '盘古中央控制台',
    'brandUrl' => \yii\helpers\Url::to(['default/index']),
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
$navItems = [];
// 生成导航链接数据 $navItems
foreach ($menu as $id => $man) {
    $sub = [];
    if (isset($man['sub'])) {
        foreach ($man['sub'] as $name => $value) {
            $sub [] = [
                'label' => $name,
                'url' => $value['url'],
                'linkOptions' => ['target' => 'sub-container']
            ];
        }
    }
    $navItems[] = [
        'label' => $man['main']['name'],
        'url' => $man['main']['url'],
        'linkOptions' => ['target' => 'sub-container'],
        'items' => $sub,
    ];
}
if(!$manager->isGuest){
    $navItems[] = [
        'label'=> "安全退出({$manager->identity->username})",
        'url' => ['default/logout'],
    ];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $navItems,
]);
NavBar::end();
?>
<iframe src="<?= \yii\helpers\Url::to(['/man/default/info']) ?>" name="sub-container" id="iframepage"
        frameborder="0" scrolling="no" width="100%" height="100%" onLoad="iFrameHeight()">
</iframe>

<script type="text/javascript" language="javascript">
    function iFrameHeight() {
        var ifm = document.getElementById("iframepage");
        var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
        if (ifm != null && subWeb != null) {
            ifm.height = subWeb.body.scrollHeight;
            ifm.width = subWeb.body.scrollWidth;
        }
    }
</script>