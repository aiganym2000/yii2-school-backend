<?php

use common\models\entity\Course;
use common\models\entity\Transaction;
use common\models\entity\Webinar;
use common\models\services\TransactionService;
use common\widgets\Panel;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Transaction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TRANSACTIONS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="transaction-view">

    <?php Panel::begin([
        'title' => $this->title,
    ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'amount',
            'pay_id',
            [
                'format' => 'html',
                'attribute' => 'user_id',
                'value' => function (Transaction $model) {
                    if ($model->user)
                        return Html::a($model->user->id, ['/admin/user/view', 'id' => $model->user_id]);
                },
            ],
            [
                'format' => 'html',
                'attribute' => 'promocode_id',
                'value' => function (Transaction $model) {
                    if ($model->promocode)
                        return Html::a($model->promocode->promo, ['/admin/promocode/view', 'id' => $model->promocode_id]);
                    else
                        return $model->promocode_id;
                },
            ],
            [
                'attribute' => 'payment_type',
                'format' => 'html',
                'value' => function (Transaction $model) {
                    return Html::tag('span', $model->getTypeLabel());
                },
            ],
            [
                'attribute' => 'cart',
                'format' => 'html',
                'value' => function (Transaction $model) {
                    try {
                        if ($model->cart == '"full_access"')
                            return 'Полный доступ';

                        $cart = json_decode($model->cart, true);
                        if (!$cart)
                            return '';

                        $text = '';
                        foreach ($cart as $item) {
                            $item = explode('-', $item);
                            if ($item[1] == TransactionService::CART_TYPE_COURSE) {
                                $model = Course::findOne($item[0]);
                                $text .= 'Курс - ';
                                $url = '/admin/course/view';
                            } else {
                                $model = Webinar::findOne($item[0]);
                                $text .= 'Вебинар - ';
                                $url = '/admin/webinar/view';
                            }

                            if ($model)
                                $text .= Html::a($model->title, [$url, 'id' => $model->id]) . "<br>";
                            else
                                $text .= "Удален<br>";
                        }

                        return $text;
                    } catch (Exception $e) {
                        return '';
                    }
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function (Transaction $model) {
                    $class = '';
                    if ($model->status === $model::STATUS_PAID) {
                        $class = 'label-success';
                    } else if ($model->status === $model::STATUS_IN_WAITING) {
                        $class = 'label-warning';
                    } else if ($model->status === $model::STATUS_CANCELLED) {
                        $class = 'label-danger';
                    } else if ($model->status === $model::STATUS_CREATED) {
                        $class = 'label-primary';
                    }

                    return Html::tag('span', $model->getStatusLabel(), ['class' => 'label ' . $class]);
                },
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
