<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\StatisticSearch;
use Yii;
use yii\web\Controller;

/**
 * StatisticController implements the CRUD actions for Statistic model.
 */
class StatisticController extends Controller
{
    /**
     * Lists all Statistic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatisticSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['statisticparams'];
            if (isset(Yii::$app->session['statisticparams']['page']))
                $_GET['page'] = Yii::$app->session['statisticparams']['page'];
        } else {
            Yii::$app->session->set('statisticparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
