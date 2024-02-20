<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\StatisticAuthorSearch;
use Yii;
use yii\web\Controller;

/**
 * StatisticAuthorController implements the CRUD actions for StatisticAuthor model.
 */
class StatisticAuthorController extends Controller
{
    /**
     * Lists all StatisticAuthor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatisticAuthorSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['statisticaparams'];
            if (isset(Yii::$app->session['statisticaparams']['page']))
                $_GET['page'] = Yii::$app->session['statisticaparams']['page'];
        } else {
            Yii::$app->session->set('statisticaparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
