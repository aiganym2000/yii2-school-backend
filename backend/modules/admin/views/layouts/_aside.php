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
                        'url' => ['/admin/default/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'USERS'),
                        'url' => ['/admin/user/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'AUTHORS'),
                        'url' => ['/admin/author/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'CATEGORIES'),
                        'url' => ['/admin/category/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'COURSES'),
                        'url' => ['/admin/course/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'CORPORATE_TRAINING'),
                        'url' => ['/admin/corporate-training/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => ['/admin/setting/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'BANNERS'),
                        'url' => ['/admin/banner/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'TRANSACTIONS'),
                        'url' => ['/admin/transaction/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'NOTIFICATIONS'),
                        'url' => ['/admin/notification/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'ACHIEVEMENTS'),
                        'url' => ['/admin/achievement/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'FEEDBACK'),
                        'url' => ['/admin/feedback/index'],
                        'icon' => 'fa-link',
                        'void' => true,
                    ],
                    [
                        'label' => Yii::t('app', 'PROMOCODES'),
                        'url' => ['/admin/promocode/index'],
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