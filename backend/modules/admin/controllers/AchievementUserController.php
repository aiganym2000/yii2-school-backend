<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\AchievementUserSearch;
use common\models\entity\AchievementUser;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * AchievementUserController implements the CRUD actions for AchievementUser model.
 */
class AchievementUserController extends BaseController
{
    /**
     * Lists all AchievementUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AchievementUserSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['auserparams'];
            if (isset(Yii::$app->session['auserparams']['page']))
                $_GET['page'] = Yii::$app->session['auserparams']['page'];
        } else {
            Yii::$app->session->set('auserparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AchievementUser model.
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
     * Finds the AchievementUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AchievementUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AchievementUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
