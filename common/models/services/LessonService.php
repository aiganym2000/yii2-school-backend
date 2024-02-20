<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Lesson;
use common\models\entity\PurchasedCourse;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;

class LessonService extends Lesson
{
    /**
     * @param $title
     * @param $description
     * @param $status
     * @param $courseId
     * @param $position
     * @param $userId
     * @param $video
     * @return LessonService
     * @throws ConflictHttpException
     */
    public static function createLesson($title, $description, $status, $courseId, $position, $userId, $video)
    {
        $lesson = new self();
        $lesson->title = $title;
        $lesson->description = $description;
        $lesson->created_user_id = $userId;
        $lesson->course_id = $courseId;
        $lesson->position = $position;
        $lesson->video = $video;
        if ($status == 'ACTIVE')
            $lesson->status = self::STATUS_ACTIVE;
        else
            $lesson->status = self::STATUS_NOT_ACTIVE;

        if (!$lesson->save())
            RequestHelper::exceptionModel($lesson);

        return $lesson;
    }

    /**
     * @param $id
     * @param $title
     * @param $description
     * @param $status
     * @param $courseId
     * @param $position
     * @param $video
     * @return LessonService|null
     * @throws ConflictHttpException
     */
    public static function updateLesson($id, $title = null, $description = null, $status = null, $courseId = null, $position = null, $video = null)
    {
        $lesson = self::findOne($id);

        if (!$lesson)
            RequestHelper::exception();

        if ($title)
            $lesson->title = $title;

        if ($description)
            $lesson->description = $description;

        if ($courseId)
            $lesson->course_id = $courseId;

        if ($position)
            $lesson->position = $position;

        if ($video)
            $lesson->video = $video;

        if ($status) {
            if ($status == 'ACTIVE')
                $lesson->status = self::STATUS_ACTIVE;
            else
                $lesson->status = self::STATUS_NOT_ACTIVE;
        }

        if (!$lesson->save())
            RequestHelper::exceptionModel($lesson);

        return $lesson;
    }

    /**
     * @param $id
     * @return Lesson
     * @throws ConflictHttpException
     */
    public static function viewLesson($id, $userId)
    {
        $model = Lesson::findOne($id);
        if (!$model)
            return RequestHelper::exception('Курс не найден');

        $pCourse = PurchasedCourse::findOne(['course_id' => $model->course_id, 'user_id' => $userId]);
        if ($pCourse)
            return $model;
        else
            return RequestHelper::exception('Курс не куплен');
    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function listLesson($courseId = null)
    {
        $userId = Yii::$app->user->id;
        if ($courseId && $userId) {
            $model = Lesson::find()
                ->where(['course_id' => $courseId])
                ->orderBy('position')
                ->all();

            $pCourse = PurchasedCourse::findOne(['course_id' => $courseId, 'user_id' => $userId]);
            if ($pCourse)
                return $model;
            else
                throw new ForbiddenHttpException('Курс не куплен');
        }

        return self::find()->all();
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'title',
            'description',
            'course_id' => function (self $model) {
                return ($table = $model->getCourse()->one()) ? $table : '';
            },
            'position',
            'id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }
}