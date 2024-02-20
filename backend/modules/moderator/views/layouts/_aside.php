<?php

use common\models\entity\User;
use common\widgets\Sidebar;

?>
<aside class="main-sidebar">
    <!--    <a href="--><? //= Url::to(['/'])?><!--" class="mini-logo visible-xs">-->
    <!--        <span class="logo-lg">Admin</span>-->
    <!--    </a>-->
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= User::findOne(['id' => Yii::$app->user->id])->fullname ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?= Sidebar::widget(
            [
                'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
                'linkTemplate' => '<a href="{url}">{icon} {label}</a>',
                'activeCssClass' => 'active',
                'items' => [
                    [
                        'label' => 'Меню',
                        'options' => [
                            'class' => 'header'
                        ],
                    ],
                    [
                        'label' => 'Главная',
                        'url' => ['/moderator/default/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => 'Пользователи',
                        'url' => ['/moderator/user/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => 'Страны',
                        'url' => ['/moderator/country/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => 'Регион',
                        'url' => ['/moderator/region/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => 'Район',
                        'url' => ['/moderator/district/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => 'Школа',
                        'url' => ['/moderator/school/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => 'Классы',
                        'url' => ['/moderator/classroom/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => 'Предметы',
                        'url' => ['/moderator/subject/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    /*[
                        'label' => Yii::t('app', 'Matching'),
                        'icon' => 'fa-compress aria-hidden="true"',
                        'url' => '',
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Medicament'),
                                'url' => ['/matching/index'],
                                'icon' => 'none',
                            ],
                        ]
                    ],*/
                ]
            ]
        );
        ?>
    </section>
    <!-- /.sidebar -->
</aside>