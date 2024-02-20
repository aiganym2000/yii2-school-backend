<?php

use common\models\entity\Question;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Question */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => strip_tags($model->course->title), 'url' => ['/admin/course/view', 'id' => $model->course_id]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="question-view">

    <?php Panel::begin([
        'title' => $this->title,
        'buttonsTemplate' => '{cancel}',
        'buttons' => [
            'cancel' => [
                'url' => ['/admin/course/view', 'id' => $model->course_id],
                'icon' => 'fa fa-reply',
                'options' => ['class' => 'btn btn-sm btn-default btn btn-primary']
            ]
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'course_id',
                'format' => 'html',
                'value' => function (Question $model) {
                    return Html::a(strip_tags($model->course->title), ['/admin/course/view', 'id' => $model->course_id]);
                }
            ],
            'text:html',
            'position',
            [
                'attribute' => 'type',
                'format' => 'html',
                'value' => function (Question $model) {
                    return $model->getTypeLabel();
                },
            ],
//            'answer',
            [
                'attribute' => 'answer',
                'format' => 'html',
                'value' => function (Question $model) {
                    if ($model->type == Question::TYPE_ONE_ANSWER || $model->type == Question::TYPE_SEVERAL_ANSWERS) {
                        $answer = json_decode($model->answer, true);

                        $text = '';
                        for ($i = 0; $i < count($answer); $i++) {
                            $right = ($answer[$i]['right'] == 1) ? ' ✓' : ' ';
                            $text .= $answer[$i]['text'] . $right . "<br>";
                        }

                        return $text;
                    } elseif ($model->type == Question::TYPE_MATCH) {
                        $answer = json_decode($model->answer, true);
                        $first = $answer['first'];
                        $second = $answer['second'];

                        $text = '';
                        for ($i = 0; $i < count($first); $i++) {
                            $text .= $first[$i]['text'] . ' : ' . $second[$i]['text'] . "<br>";
                        }

                        return $text;
                    } elseif ($model->type == Question::TYPE_PLACEMENT) {
                        $answer = json_decode($model->answer, true);

                        $text = '';
                        for ($i = 0; $i < count($answer); $i++) {
                            $text .= ($i + 1) . '. ' . $answer[$i]['text'] . "<br>";
                        }

                        return $text;
                    }

                    return $model->answer;
                },
            ],
            [
                'attribute' => 'created_user_id',
                'format' => 'html',
                'value' => function (Question $model) {
                    if ($model->createdUser)
                        return Html::a($model->createdUser->id, ['/admin/user/view', 'id' => $model->created_user_id]);
                }
            ],
            'created_at',
            'updated_at',
        ]
    ]) ?>

    <?php Panel::end() ?>

</div>
