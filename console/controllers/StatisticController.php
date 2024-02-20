<?php

namespace console\controllers;

use common\models\entity\Course;
use common\models\entity\Statistic;
use common\models\entity\StatisticAuthor;
use common\models\entity\StatisticPromocode;
use common\models\entity\Transaction;
use common\models\entity\Webinar;
use common\models\services\TransactionService;
use Exception;
use yii\console\Controller;

class StatisticController extends Controller
{
    public function actionIndex($dateFrom = null, $dateTo = null)
    {
        if ($dateFrom && $dateTo) {
            $dates = self::getDates($dateFrom, $dateTo);
        } else {
            $dates[] = date("Y-m-d");
        }

        foreach ($dates as $date) {
            $authors = StatisticAuthor::findAll(['date' => $date]);
            foreach ($authors as $author) {
                $author->count = 0;
                $author->sum = 0;
                $author->save();
            }

            $promocodes = StatisticPromocode::findAll(['date' => $date]);
            foreach ($promocodes as $promocode) {
                $promocode->count = 0;
                $promocode->save();
            }

            $transactions = Transaction::find()
                ->where(['status' => Transaction::STATUS_PAID])
                ->andWhere(['like', 'created_at', $date])
                ->all();

            $averageCheck = 0;
            $count = 0;
            $authors = [];
            $promocodes = [];
            foreach ($transactions as $transaction) {
                $averageCheck += $transaction->amount;
                $count++;

                $percent = 1;
                $promocode = null;
                if ($transaction->promocode_id) {
                    $promocode = $transaction->promocode;
                    if ($promocode) {
                        $promocodeSt = StatisticPromocode::findOne(['promo' => $promocode->promo, 'date' => $date]);
                        if (!$promocodeSt) {
                            $promocodeSt = new StatisticPromocode();
                            $promocodeSt->date = $date;
                            $promocodeSt->count = 0;
                            $promocodeSt->promo = $promocode->promo;
                        }
                        $promocodeSt->count += 1;
                        $promocodeSt->save();

                        if (!isset($promocodes[$promocode->promo]))
                            $promocodes[$promocode->promo] = 0;

                        $promocodes[$promocode->promo] += 1;
                        $percent = (100 - $promocode->percent) / 100;
                    }
                }

                try {
                    $cart = json_decode($transaction->cart, true);
                    if ($cart) {
                        foreach ($cart as $item) {
                            $item = explode('-', $item);
                            if ($item[1] == TransactionService::CART_TYPE_COURSE) {
                                $model = Course::findOne($item[0]);
                                $author = $model->author;
                            } else {
                                $model = Webinar::findOne($item[0]);
                                $author = $model->course->author;
                            }

                            $authorSt = StatisticAuthor::findOne(['author_id' => $author->id, 'date' => $date]);
                            if (!$authorSt) {
                                $authorSt = new StatisticAuthor();
                                $authorSt->date = $date;
                                $authorSt->count = 0;
                                $authorSt->sum = 0;
                                $authorSt->author_id = $author->id;
                            }
                            $authorSt->count += 1;
                            $authorSt->sum += $model->price * $percent;
                            $authorSt->save();

                            if (!isset($authors[$author->id])) {
                                $authors[$author->id]['fio'] = $author->fio;
                                $authors[$author->id]['count'] = 0;
                                $authors[$author->id]['sum'] = 0;
                            }

                            $authors[$author->id]['count'] += 1;
                            $authors[$author->id]['sum'] += $model->price * $percent;
                        }
                    }
                } catch (Exception $e) {
                }
            }

            $today = Statistic::find()
                ->where(['date' => $date])
                ->one();
            if (!$today) {
                $today = new Statistic();
                $today->date = $date;
            }

            if ($count)
                $today->average_check = $averageCheck / $count;
            else
                $today->average_check = 0;
            $today->promocode_json = json_encode($promocodes);
            $today->author_json = json_encode($authors);
            $today->save();
        }
        $this->stdout('Done!' . PHP_EOL);
    }

    public static function getDates($startTime, $endTime)
    {
        $day = 86400;
        $format = 'Y-m-d';
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $numDays = round(($endTime - $startTime) / $day) + 1;

        $days = [];
        for ($i = 0; $i < $numDays; $i++) {
            $days[] = date($format, ($startTime + ($i * $day)));
        }

        return $days;
    }
}