<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\search\AuthorSearch;
use common\models\entity\Author;
use common\models\helpers\UploadHelper;
use common\models\helpers\VideoUpload;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use Yii;
use yii\imagine\Image;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorController extends BaseController
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
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
     * Lists all Author models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $params = Yii::$app->request->queryParams;

        if (count($params) == 0) {
            $params = Yii::$app->session['authorparams'];
            if (isset(Yii::$app->session['authorparams']['page']))
                $_GET['page'] = Yii::$app->session['authorparams']['page'];
        } else {
            Yii::$app->session->set('authorparams', $params);
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Author model.
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
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Author::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Author();
        $model->created_user = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $width_param = Yii::$app->params['img_width'];
            $photo = Yii::getAlias('@static') . '/web/images/' . Author::IMG_PATH . '/' . $model->photo;
            list($width, $height, $type, $attr) = getimagesize($photo);
            $height /= $width / $width_param;
            $model->small_photo = uniqid() . UploadHelper::getExtension($photo);
            Image::resize($photo, $width_param, $height)
                ->save(Yii::getAlias('@static') . '/web/images/' . Author::IMG_PATH . '/' . $model->small_photo, ['quality' => 100]);
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $video = UploadedFile::getInstance($model, 'videoFile');
            $dir = UploadHelper::createDir($video, 'video');
            $model->video = $dir['path'] . $dir['dir'];
            $path = $dir['full_path'] . $model->video;

            if ($video) {
                $video->saveAs($path);
            }

            $width_param = Yii::$app->params['img_width'];
            $photo = Yii::getAlias('@static') . '/web/images/' . Author::IMG_PATH . '/' . $model->photo;
            list($width, $height, $type, $attr) = getimagesize($photo);
            $height /= $width / $width_param;
            $model->small_photo = uniqid() . UploadHelper::getExtension($photo);
            Image::resize($photo, $width_param, $height)
                ->save(Yii::getAlias('@static') . '/web/images/' . Author::IMG_PATH . '/' . $model->small_photo, ['quality' => 100]);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Author model.
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
