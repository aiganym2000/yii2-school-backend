<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\AchievementUserSearch;
use backend\modules\admin\models\search\PurchasedCourseSearch;
use backend\modules\admin\models\search\UserSearch;
use backend\modules\admin\models\UserDataModel;
use common\models\entity\User;
use Throwable;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    public function actions()
    {
        return [
            'img-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@static/temp/',
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['userparams'];
            if (isset(Yii::$app->session['userparams']['page']))
                $_GET['page'] = Yii::$app->session['userparams']['page'];
        } else {
            Yii::$app->session->set('userparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) == 1) {
            $params = Yii::$app->session['auserparams'];
            if (isset(Yii::$app->session['auserparams']['page']))
                $_GET['page'] = Yii::$app->session['auserparams']['page'];
        } else {
            Yii::$app->session->set('auserparams', $params);
        }

        $courseSearchModel = new PurchasedCourseSearch();
        $courseDataProvider = $courseSearchModel->search($params, $id);

        $userSearchModel = new AchievementUserSearch();
        $userDataProvider = $userSearchModel->search($params, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'userSearchModel' => $userSearchModel,
            'userDataProvider' => $userDataProvider,
            'courseSearchModel' => $courseSearchModel,
            'courseDataProvider' => $courseDataProvider,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->generateAuthKey();
        $model->ref_string = Yii::$app->security->generateRandomString();

        if ($model->load(Yii::$app->request->post())) {
//            print_r($model);
            if ($model->password_new) {
                $model->setPassword($model->password_new);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'NO_PASSWORD'));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->password_new) {
                $model->setPassword($model->password_new);
                $model->save();
            }
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->role == User::ROLE_ADMIN || $model->id == 1) {
            Yii::$app->session->setFlash('error', 'Вы не можете удалить администратора');
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionExcel($start = null, $end = null)
    {
        $bus = new UserDataModel();
        $bus->getit($start, $end);
        exit();
    }
}
