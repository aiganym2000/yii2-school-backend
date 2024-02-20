<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\LessonSearch;
use common\models\entity\Lesson;
use common\models\helpers\VideoUpload;
use getID3;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * LessonController implements the CRUD actions for Lesson model.
 */
class LessonController extends BaseController
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'video-upload' => [
                'class' => VideoUpload::className(),
                'path' => '@static/temp/',
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Lists all Lesson models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LessonSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['lessonparams'];
            if (isset(Yii::$app->session['lessonparams']['page']))
                $_GET['page'] = Yii::$app->session['lessonparams']['page'];
        } else {
            Yii::$app->session->set('lessonparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lesson model.
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
     * Finds the Lesson model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lesson the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lesson::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Lesson model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($courseId)
    {
        $model = new Lesson();
        $model->course_id = $courseId;
        $model->created_user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $course = $model->course;
            if ($model->video) {
                $getID3 = new getID3();
                $getID3 = $getID3->analyze(Yii::getAlias('@static') . '/web/video/' . $model->video);
                $sum = 0;
                if (isset($getID3['playtime_seconds'])) {
                    $model->time = $getID3['playtime_seconds'];
                    $sum = $model->time;
                }
                $lessons = Lesson::findAll(['course_id' => $courseId]);
                foreach ($lessons as $lesson) {
                    $sum += (double)$lesson->time;
                }
                $course->time = $sum;
            }
            if ($course->save() && $model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Lesson model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $course = $model->course;
            $getID3 = new getID3();
            $getID3 = $getID3->analyze(Yii::getAlias('@static') . '/web/video/' . $model->video);
            $sum = 0;
            if (isset($getID3['playtime_seconds'])) {
                $model->time = $getID3['playtime_seconds'];
                $sum = $model->time;
            }
            $lessons = Lesson::findAll(['course_id' => $model->course_id]);
            foreach ($lessons as $lesson) {
                $sum += (double)$lesson->time;
            }
            $course->time = $sum;
            if ($course->save() && $model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
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
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }
}
