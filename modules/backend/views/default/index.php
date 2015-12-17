<?php

/* @var $this \yii\web\View */
/* @var $menu array */

use \yii\helpers\Url;

\app\modules\backend\assets\AdminLTEAsset::register($this);
/* @var $manager \yii\web\User */
$manager = Yii::$app->manager;
?>

<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>PG</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Pangu</b>OS</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas"></a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?= $manager->identity->username ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <p>
                                    <?= $manager->identity->username ?> -
                                    <?= ($role = current(Yii::$app->authManager->getRolesByUser($manager->id))) ? $role->description : '?' ?>
                                    <small>Since <?= date('Y/m/d', $manager->identity->created_at) ?></small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?= Url::to(['/backend/manager/reset-password']) ?>" target="sub-container"
                                       class="btn btn-default btn-flat">
                                        <?= Yii::t('backend', 'Reset Password') ?>
                                    </a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?= Url::to(['/backend/default/logout']) ?>"
                                       class="btn btn-default btn-flat"><?= Yii::t('backend', 'Logout') ?></a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header"></li>
                <?php foreach ($menu as $item): ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-plus"></i>
                            <span><?= $item['label'] ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php foreach ($item['items'] as $i): ?>
                                <li>
                                    <a href="<?= Url::to($i['url']) ?>" target="sub-container">
                                        <i class="fa fa-eraser"></i>
                                        <?= $i['label'] ?>
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            <iframe src="<?= Url::to(Yii::$app->params['backend.default.page']) ?>" name="sub-container" id="iframepage"
                    frameborder="0" scrolling="auto" style="width: 100%;">
            </iframe>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    !-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Powerred by <b>Pangu</b>OS
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; <?= date('Y') ?> <a href="#">PGOS</a>.</strong> All rights reserved.
    </footer>

</div>
<!-- ./wrapper -->

<script type="text/javascript" language="javascript">
    <?php $this->beginBlock('js_ready');?>
    $('#iframepage').load(function () {
        $(this).height(window.innerHeight);
    });
    $('.treeview-menu li').click(function () {
        $('.treeview-menu li').each(function () {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
    });
    <?php $this->endBlock();$this->registerJs($this->blocks['js_ready']);?>
</script>

