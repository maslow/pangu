<?php

/* @var $this \yii\web\View */

use app\modules\man\Module;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* 获取所有模块man配置信息，转换url，生成管理菜单($menu) */
$modules = Module::getInstance()->moduleManager->getModules();
$menu = [];
foreach ($modules as $id => $m) {
    if ($m['man'] !== false) {
        $m['man']['main']['url'] = Yii::$app->request->scriptUrl . '/' . $id . '/' . $m['man']['main']['url'];
        if(isset($m['man']['sub'])){
            foreach($m['man']['sub'] as $name=>$v){
                $m['man']['sub'][$name]['url'] = Yii::$app->request->scriptUrl.'/'.$id.'/'.$v['url'];
            }
        }
        $menu[$id] = $m['man'];
    }
}
?>

<?php
NavBar::begin([
    'brandLabel' => '盘古开发平台',
    'brandUrl' => \yii\helpers\Url::to(['default/index']),
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
$navItems = [
];
// 生成导航链接数据
foreach ($menu as $id => $man) {
    $sub = [];
    if(isset($man['sub'])){
        foreach($man['sub'] as $name => $value){
            $sub [] = [
                'label'=>$name,
                'url'=>$value['url'],
                'linkOptions'=>['target'=>'sub-container']
            ];
        }
    }
    $navItems[] = [
        'label' => $man['main']['name'],
        'url' => $man['main']['url'],
        'linkOptions' => ['target' => 'sub-container'],
        'items'=>$sub,
    ];
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $navItems,
]);
NavBar::end();
?>
<iframe name="sub-container" id="iframepage" frameborder="0" scrolling="no" width="100%" height="100%" onLoad="iFrameHeight()">
</iframe>

<script type="text/javascript" language="javascript">
    function iFrameHeight() {
        var ifm= document.getElementById("iframepage");
        var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
        if(ifm != null && subWeb != null) {
            ifm.height = subWeb.body.scrollHeight;
            ifm.width = subWeb.body.scrollWidth;
        }
    }
</script>