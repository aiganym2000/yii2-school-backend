<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\PurchasedCourseSearch;
use common\models\entity\PurchasedCourse;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PurchasedCourseController implements the CRUD actions for PurchasedCourse model.
 */
class PurchasedCourseController extends Controller
{
    /**
     * Lists all PurchasedCourse models.
     * @return mixed
     */
    public function actionIndex($userId)
    {
        $searchModel = new PurchasedCourseSearch();

        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['pcourseparams'];
            if (isset(Yii::$app->session['pcourseparams']['page']))
                $_GET['page'] = Yii::$app->session['pcourseparams']['page'];
        } else {
            Yii::$app->session->set('pcourseparams', $params);
        }

        $dataProvider = $searchModel->search($params, $userId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new PurchasedCourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($userId)
    {
        $model = new PurchasedCourse();
        $model->user_id = $userId;
        $model->price = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/admin/user/view', 'id' => $model->user_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PurchasedCourse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the PurchasedCourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchasedCourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchasedCourse::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
