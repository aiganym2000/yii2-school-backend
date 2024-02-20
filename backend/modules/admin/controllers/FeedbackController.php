<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\FeedbackSearch;
use common\models\entity\Feedback;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * FeedbackController implements the CRUD actions for Feedback model.
 */
class FeedbackController extends BaseController
{
    /**
     * Lists all Feedback models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeedbackSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['feedbackparams'];
            if (isset(Yii::$app->session['feedbackparams']['page']))
                $_GET['page'] = Yii::$app->session['feedbackparams']['page'];
        } else {
            Yii::$app->session->set('feedbackparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Feedback model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Feedback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feedback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feedback::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
