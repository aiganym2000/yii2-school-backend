<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\PromocodeDataModel;
use backend\modules\admin\models\PromoForm;
use backend\modules\admin\models\search\PromocodeSearch;
use common\models\entity\Promocode;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * PromocodeController implements the CRUD actions for Promocode model.
 */
class PromocodeController extends BaseController
{
    /**
     * Lists all Promocode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PromocodeSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['promocodeparams'];
            if (isset(Yii::$app->session['promocodeparams']['page']))
                $_GET['page'] = Yii::$app->session['promocodeparams']['page'];
        } else {
            Yii::$app->session->set('promocodeparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        $promocodeModel = new PromoForm();
        if ($promocodeModel->load(Yii::$app->request->post()) && $promocodeModel->delete()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'promocodeModel' => $promocodeModel,
        ]);
    }

    /**
     * Displays a single Promocode model.
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
     * Finds the Promocode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Promocode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Promocode::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Promocode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Promocode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->count > 1) {
                for ($i = 0; $i < $model->count - 1; $i++) {
                    $newModel = new Promocode();
                    $newModel->count = 0;
                    $newModel->promo = $model->promo;
                    $newModel->percent = $model->percent;
                    $newModel->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lesson model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status == Promocode::STATUS_INACTIVE) {
            Yii::$app->session->setFlash('error', 'Вы не можете удалить использованный промокод');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionExcel($start = null, $end = null)
    {
        $bus = new PromocodeDataModel();
        $bus->getit($start, $end);
        exit();
    }
}
