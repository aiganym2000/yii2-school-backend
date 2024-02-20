<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\StatisticAuthorSearch;
use backend\modules\admin\models\search\StatisticPromocodeSearch;
use backend\modules\admin\models\search\StatisticSearch;
use common\models\entity\Author;
use common\models\entity\Statistic;
use common\models\entity\StatisticAuthor;
use common\models\entity\StatisticPromocode;
use Yii;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends BaseController
{
    public function actionDate()
    {
        if (!Yii::$app->session->isActive) {
            Yii::$app->session->open();
        }
        Yii::$app->session->set('dateFrom', Yii::$app->request->get('dateFrom'));
        Yii::$app->session->set('dateTo', Yii::$app->request->get('dateTo'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['statisticparams'];
            if (isset(Yii::$app->session['statisticparams']['page']))
                $_GET['page'] = Yii::$app->session['statisticparams']['page'];
        } else {
            Yii::$app->session->set('statisticparams', $params);
        }

        $statisticSearchModel = new StatisticSearch();
        $statisticDataProvider = $statisticSearchModel->search($params);

        $authorSearchModel = new StatisticAuthorSearch();
        $authorDataProvider = $authorSearchModel->search([]);

        $promocodeSearchModel = new StatisticPromocodeSearch();
        $promocodeDataProvider = $promocodeSearchModel->search([]);

        $dateFrom = Yii::$app->session->get('dateFrom');
        $dateTo = Yii::$app->session->get('dateTo');

        if (!$dateFrom)
            Yii::$app->session->set('dateFrom', date('Y-m-d'));
        if (!$dateTo)
            Yii::$app->session->set('dateTo', date('Y-m-d'));

        $analytic = Statistic::find()
            ->orderBy(['date' => SORT_ASC])
            ->all();
        $data = [];
        $sums = [];
        foreach ($analytic as $item) {
            $sums[] = $item->average_check;
            array_push($data, $item['date']);
        }

        $authorLabels = [];
        $counts = [];
        $sum = 0;
        $authors = Author::find()->all();
        $authorhbc = [];
        foreach ($authors as $author) {
            $stCount = StatisticAuthor::find()
                ->where(['author_id' => $author->id]);

            if ($dateFrom)
                $stCount = $stCount->andWhere(['>=', 'date', $dateFrom]);
            if ($dateTo)
                $stCount = $stCount->andWhere(['<=', 'date', $dateTo]);

            $stCount = $stCount->sum('count');
            $stSum = StatisticAuthor::find()
                ->where(['author_id' => $author->id]);

            if ($dateFrom)
                $stSum = $stSum->andWhere(['>=', 'date', $dateFrom]);
            if ($dateTo)
                $stSum = $stSum->andWhere(['<=', 'date', $dateTo]);

            $stSum = $stSum->sum('sum');
            if ($stCount) {
                $authorLabels[$author->fio . ', ' . $stCount . ', ' . $stSum . 'р'] = 0;
                $counts[$author->fio . ', ' . $stCount . ', ' . $stSum . 'р'] = $stCount;
                $sum += $stCount;
                $authorhbc = '#999';
            }
        }

        foreach ($authorLabels as $key => $label) {
            if ($sum)
                $authorLabels[$key] = $counts[$key] / $sum;
        }

        $promocodes = [];
        $counts = [];
        $promos = StatisticPromocode::find();

        if ($dateFrom)
            $promos = $promos->andWhere(['>=', 'date', $dateFrom]);
        if ($dateTo)
            $promos = $promos->andWhere(['<=', 'date', $dateTo]);

        $promos = $promos
            ->select('promo')
            ->distinct()
            ->all();

        $sum = 0;
        $promocodehbc = [];
        foreach ($promos as $promo) {
            $stCount = StatisticPromocode::find()->where(['promo' => $promo->promo]);

            if ($dateFrom)
                $stCount = $stCount->andWhere(['>=', 'date', $dateFrom]);
            if ($dateTo)
                $stCount = $stCount->andWhere(['<=', 'date', $dateTo]);

            $stCount = $stCount->sum('count');
            if ($stCount) {
                $promocodes[$promo->promo . ', ' . $stCount] = 0;
                $counts[$promo->promo . ', ' . $stCount] = $stCount;
                $sum += $stCount;
                $promocodehbc = '#999';
            }
        }

        foreach ($promocodes as $key => $value) {
            if ($sum)
                $promocodes[$key] = $counts[$key] / $sum;
        }

        $height = 200;
        if (count($promocodes) > 20 || count($authorLabels) > 20)
            $height = 400;

        array_multisort($promocodes, SORT_DESC);
        array_multisort($authorLabels, SORT_DESC);

        return $this->render('index', compact('sums', 'data', 'statisticSearchModel', 'statisticDataProvider', 'authorSearchModel', 'authorDataProvider', 'promocodeSearchModel', 'promocodeDataProvider', 'promocodes', 'promocodehbc', 'authorhbc', 'authorLabels', 'height'));
    }
}
