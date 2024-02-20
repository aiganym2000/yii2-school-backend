<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\TransactionSearch;
use common\models\entity\Transaction;
use common\models\services\TransactionService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends BaseController
{
    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['transactionparams'];
            if (isset(Yii::$app->session['transactionparams']['page']))
                $_GET['page'] = Yii::$app->session['transactionparams']['page'];
        } else {
            Yii::$app->session->set('transactionparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->pay_id && $model->status != Transaction::STATUS_PAID && $model->status != Transaction::STATUS_CANCELLED) {
            if ($model->payment_type == Transaction::TYPE_CRYPT)
                TransactionService::updateStatusCrypt($model);
            elseif ($model->payment_type == Transaction::TYPE_CLOUD_PAYMENTS)
                TransactionService::updateStatusCp($model);
            elseif ($model->payment_type == Transaction::TYPE_STRIPE)
                TransactionService::updateStatusStripe($model);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
