<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\CourseSearch;
use backend\modules\admin\models\search\LessonSearch;
use backend\modules\admin\models\search\QuestionSearch;
use backend\modules\admin\models\search\WebinarSearch;
use common\models\entity\Course;
use common\models\entity\Lesson;
use common\models\entity\Question;
use common\models\helpers\VideoUpload;
use himiklab\sortablegrid\SortableGridAction;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use Yii;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

/**
 * CourseController implements the CRUD actions for Course model.
 */
class CourseController extends BaseController
{
    public function actions()
    {
        return [
            'sortItem' => [
                'class' => SortableGridAction::className(),
                'modelName' => Course::className(),
            ],
            'sort' => [
                'class' => SortableGridAction::className(),
                'modelName' => Lesson::className(),
            ],
            'sortQuestion' => [
                'class' => SortableGridAction::className(),
                'modelName' => Question::className(),
            ],
            'img-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@static/temp/',
            ],
            'video-upload' => [
                'class' => VideoUpload::className(),
                'path' => '@static/temp/',
            ],
        ];
    }

    /**
     * Lists all Course models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CourseSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['courseparams'];
            if (isset(Yii::$app->session['courseparams']['page']))
                $_GET['page'] = Yii::$app->session['courseparams']['page'];
        } else {
            Yii::$app->session->set('courseparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Course model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) == 1) {
            $params = Yii::$app->session['webinarparams'];
            if (isset(Yii::$app->session['webinarparams']['page']))
                $_GET['page'] = Yii::$app->session['webinarparams']['page'];
        } else {
            Yii::$app->session->set('webinarparams', $params);
        }

        $webinarSearchModel = new WebinarSearch();
        $webinarDataProvider = $webinarSearchModel->search($params, $id);

        $lessonSearchModel = new LessonSearch();
        $lessonDataProvider = $lessonSearchModel->search($params, $id);

        $questionSearchModel = new QuestionSearch();
        $questionDataProvider = $questionSearchModel->search($params, $id);

        $cookies = Yii::$app->response->cookies;
        $cookies->add(new Cookie([
            'name' => 'sortableId',
            'value' => $id,
        ]));

        return $this->render('view', [
            'model' => $this->findModel($id),
            'webinarSearchModel' => $webinarSearchModel,
            'webinarDataProvider' => $webinarDataProvider,
            'lessonSearchModel' => $lessonSearchModel,
            'lessonDataProvider' => $lessonDataProvider,
            'questionSearchModel' => $questionSearchModel,
            'questionDataProvider' => $questionDataProvider,
        ]);
    }

    /**
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Course model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Course();
        $model->created_user = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Course model.
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

    /**
     * Deletes an existing Course model.
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
}
