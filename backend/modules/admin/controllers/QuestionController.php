<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\QuestionSearch;
use common\models\entity\Question;
use common\models\services\QuestionService;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends BaseController
{
    /**
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['questionparams'];
            if (isset(Yii::$app->session['questionparams']['page']))
                $_GET['page'] = Yii::$app->session['questionparams']['page'];
        } else {
            Yii::$app->session->set('questionparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $questions = Question::find()
            ->where(['course_id' => $model->course_id])
            ->orderBy('position')
            ->all();
        $i = 1;
        foreach ($questions as $question) {
            $question->position = $i;
            $question->save();
            $i++;
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($courseId, $type)
    {
        $view = QuestionService::getView($type);
        if (!$view)
            return $this->redirect(['/admin/course/view', 'id' => $courseId]);

        $model = new Question();
        $model->created_user_id = Yii::$app->user->id;
        $model->course_id = $courseId;
        $model->type = $type;

        if ($model->load(Yii::$app->request->post())) {
            $answer = Yii::$app->request->post('Answer');
            $answer = QuestionService::getAnswer($type, $answer);

            $model->answer = $answer;
            if ($answer && $model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'view' => $view,
        ]);
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $view = QuestionService::getView($model->type);
        if (!$view)
            return $this->redirect(['view', 'id' => $model->id]);

        if ($model->load(Yii::$app->request->post())) {
            $answer = Yii::$app->request->post('Answer');
            $answer = QuestionService::getAnswer($model->type, $answer);

            $model->answer = $answer;
            if ($answer && $model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'view' => $view,
        ]);
    }

    /**
     * Deletes an existing Question model.
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
