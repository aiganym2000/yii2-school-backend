<?php

use backend\modules\admin\models\search\StatisticAuthorSearch;
use backend\modules\admin\models\search\StatisticPromocodeSearch;
use backend\modules\admin\models\search\StatisticSearch;
use common\widgets\Panel;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Url;
use common\models\pay\CryptPay;

/* @var $this yii\web\View */
/* @var $data array */
/* @var $sums array */
/* @var $authorLabels array */
/* @var $authorhbc array */
/* @var $promocodes array */
/* @var $promocodehbc array */
/* @var $height int */
/* @var $statisticSearchModel StatisticSearch */
/* @var $statisticDataProvider yii\data\ActiveDataProvider */
/* @var $authorSearchModel StatisticAuthorSearch */
/* @var $authorDataProvider yii\data\ActiveDataProvider */
/* @var $promocodeSearchModel StatisticPromocodeSearch */
/* @var $promocodeDataProvider yii\data\ActiveDataProvider */

$this->title = 'Администрация';

$pay = new CryptPay();
print_r($pay->create(100, 500000000000, 'RUB'));
?>
<section class="content">
    <div class="row">
        <?php Panel::begin() ?>
        <div class="row">
            <div class="col-lg-12">
                <?= ChartJs::widget([
                    'type' => 'line',
                    'options' => [
                        'height' => 500,
                        'width' => 1000
                    ],
                    'data' => [
                        'labels' => $data,
                        'datasets' => [
                            [
                                'label' => "Средний чек",
                                'backgroundColor' => "rgba(102, 255, 51,0.5)",
                                'borderColor' => "rgba(51, 204, 51,1)",
                                'pointBackgroundColor' => "rgba(102, 255, 51,1)",
                                'pointBorderColor' => "#fff",
                                'pointHoverBackgroundColor' => "#fff",
                                'pointHoverBorderColor' => "rgba(102, 255, 51,1)",
                                'data' => $sums
                            ],
                        ]
                    ]
                ]);
                ?>
            </div>
        </div>
        <?php Panel::end() ?>
        <?php Panel::begin() ?>
        <div class="row">
            <form action="<?= Url::to('/admin/default/date') ?>">
                <p class="history-date">
                    <label for="date">С: </label>
                    <input type="date" id="date" name="dateFrom" value="<?= Yii::$app->session->get('dateFrom') ?>">
                </p>
                <p class="history-date">
                    <label for="date">По: </label>
                    <input type="date" id="date" name="dateTo" value="<?= Yii::$app->session->get('dateTo') ?>">
                </p>
                <p class="history-date">
                    <button type="submit">Поиск</button>
                </p>
            </form>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= ChartJs::widget([
                    'type' => 'pie',
                    'options' => [
                        'height' => $height,
                    ],
                    'id' => 'structurePie',
                    'data' => [
                        'radius' => "90%",
                        'labels' => array_keys($authorLabels),
                        'datasets' => [
                            [
                                'data' => array_values($authorLabels),
                                'label' => '',
                                'borderWidth' => 1,
                                'backgroundColor' => [
                                    '#ADC3FF',
                                    '#FF9A9A',
                                    'rgba(190, 124, 145, 0.8)',
                                    '#FFD1DC',
                                    '#EFA94A',
                                    '#5D9B9B',
                                    '#A18594',
                                    '#77DD77',
                                    '#FF7514',
                                    '#FF8C69',
                                    '#FF9BAA',
                                    '#FFB28B',
                                    '#FCE883',
                                    '#BEBD7F',
                                    '#C6DF90',
                                    '#99FF99',
                                    '#AFDAFC',
                                    '#E6E6FA',
                                ],
                                'hoverBorderColor' => $authorhbc,
                            ]
                        ]
                    ],
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= ChartJs::widget([
                    'type' => 'pie',
                    'options' => [
                        'height' => $height,
                    ],
                    'id' => 'structurePie2',
                    'data' => [
                        'radius' => "90%",
                        'labels' => array_keys($promocodes),
                        'datasets' => [
                            [
                                'data' => array_values($promocodes),
                                'label' => '',
                                'borderWidth' => 1,
                                'backgroundColor' => [
                                    '#ADC3FF',
                                    '#FF9A9A',
                                    'rgba(190, 124, 145, 0.8)',
                                    '#FFD1DC',
                                    '#EFA94A',
                                    '#5D9B9B',
                                    '#A18594',
                                    '#77DD77',
                                    '#FF7514',
                                    '#FF8C69',
                                    '#FF9BAA',
                                    '#FFB28B',
                                    '#FCE883',
                                    '#BEBD7F',
                                    '#C6DF90',
                                    '#99FF99',
                                    '#AFDAFC',
                                    '#E6E6FA',
                                ],
                                'hoverBorderColor' => $promocodehbc,
                            ]
                        ]
                    ],
                ]) ?>
            </div>
        </div>
        <?php Panel::end() ?>
        <?php Panel::begin() ?>
        <div class="row">
            <div class="col-md-6">
                <?= $this->render('/statistic-author/index', [
                    'searchModel' => $authorSearchModel,
                    'dataProvider' => $authorDataProvider,
                ]); ?>
            </div>
            <div class="col-md-6">
                <?= $this->render('/statistic-promocode/index', [
                    'searchModel' => $promocodeSearchModel,
                    'dataProvider' => $promocodeDataProvider,
                ]); ?>
            </div>
        </div>
        <?php Panel::end() ?>
        <?= $this->render('/statistic/index', [
            'searchModel' => $statisticSearchModel,
            'dataProvider' => $statisticDataProvider,
        ]); ?>
    </div>
</section>
<style>
    .history-date {
        float: left;
        margin-left: 10px;
        margin-right: 5px
    }
</style>
