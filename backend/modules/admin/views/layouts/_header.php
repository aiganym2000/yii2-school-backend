<?php

use common\models\entity\User;
use yii\helpers\Url;

?>
<header class="main-header">
    <!-- Logo -->
    <a href="<?= Url::to(['/']) ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Админ-Панель</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Админ-Панель</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" data-toggle="dropdown">
                        <img src="/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?= User::findOne(['id' => Yii::$app->user->id])->fullname ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= Url::to(['/admin/user/view', 'id' => Yii::$app->user->id]) ?>"
                                   class="btn btn-default btn-flat">Профиль</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?= Url::to(['/site/logout']) ?>" data-method="post"
                                   class="btn btn-default btn-flat">Логаут</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown user user-menu">
                    <a href="<?= Url::to(['/site/logout']) ?>" class="dropdown-toggle">Логаут</a>
                </li>
            </ul>
        </div>
    </nav>
</header>