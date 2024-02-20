<?php

use backend\modules\admin\models\search\LessonSearch;
use backend\modules\admin\models\search\QuestionSearch;
use backend\modules\admin\models\search\WebinarSearch;
use common\models\entity\Course;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Course */
/* @var $webinarSearchModel WebinarSearch */
/* @var $webinarDataProvider yii\data\ActiveDataProvider */
/* @var $lessonSearchModel LessonSearch */
/* @var $lessonDataProvider yii\data\ActiveDataProvider */
/* @var $questionSearchModel QuestionSearch */
/* @var $questionDataProvider yii\data\ActiveDataProvider */

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'COURSES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="course-view">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:html',
            'description:html',
            'short_description:html',
            [
                'attribute' => 'photo',
                'value' => function (Course $model) {
                    return Html::img($model->getImgUrl(), ['width' => '120px']);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'price_photo',
                'value' => function (Course $model) {
                    return Html::img($model->getPriceImgUrl(), ['width' => '120px']);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'trailer',
                'value' => function (Course $model) {
                    return '<video width="400" height="300" controls>
                              <source src="' . $model->getVideoUrl() . '" type="video/mp4">
                            </video>';
                },
                'format' => 'raw',
            ],
            'vimeo',
            'apple_id',
            [
                'attribute' => 'author_id',
                'format' => 'html',
                'value' => function (Course $model) {
                    return Html::a($model->author->fio, ['/admin/author/view', 'id' => $model->author_id]);
                }
            ],
            [
                'attribute' => 'category_id',
                'format' => 'html',
                'value' => function (Course $model) {
                    return Html::a($model->category->title, ['/admin/category/view', 'id' => $model->category_id]);
                }
            ],
            'price',
            'position',
            [
                'attribute' => 'created_user',
                'format' => 'html',
                'value' => function (Course $model) {
                    if ($model->createdUser)
                        return Html::a($model->createdUser->id, ['/admin/user/view', 'id' => $model->created_user]);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (Course $model) {
                    if ($model->status === $model::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } else if ($model->status === $model::STATUS_NOT_ACTIVE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }
                    return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                },
            ],
            'created_at',
            'updated_at',
        ]
    ]) ?>

    <?php Panel::end() ?>

    <?php Panel::begin() ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#lesson" data-toggle="tab"><h4><?= Yii::t('app', 'LESSONS') ?></h4></a></li>
            <li><a href="#question" data-toggle="tab"><h4><?= Yii::t('app', 'QUESTIONS') ?></h4></a></li>
            <li><a href="#webinar" data-toggle="tab"><h4><?= Yii::t('app', 'WEBINARS') ?></h4></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="lesson">
                <?= $this->render('/lesson/index', [
                    'searchModel' => $lessonSearchModel,
                    'dataProvider' => $lessonDataProvider,
                    'courseId' => $model->id,
                ]); ?>
            </div>
            <div class="tab-pane" id="question">
                <?= $this->render('/question/index', [
                    'searchModel' => $questionSearchModel,
                    'dataProvider' => $questionDataProvider,
                    'courseId' => $model->id,
                ]); ?>
            </div>
            <div class="tab-pane" id="webinar">
                <?= $this->render('/webinar/index', [
                    'searchModel' => $webinarSearchModel,
                    'dataProvider' => $webinarDataProvider,
                    'courseId' => $model->id,
                ]); ?>
            </div>
        </div>
    </div>

    <?php Panel::end() ?>

</div>
<script src="/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>