<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\StatisticPromocodeSearch;
use Yii;
use yii\web\Controller;

/**
 * StatisticPromocodeController implements the CRUD actions for StatisticPromocode model.
 */
class StatisticPromocodeController extends Controller
{
    /**
     * Lists all StatisticPromocode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatisticPromocodeSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['statisticpparams'];
            if (isset(Yii::$app->session['statisticpparams']['page']))
                $_GET['page'] = Yii::$app->session['statisticpparams']['page'];
        } else {
            Yii::$app->session->set('statisticpparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
