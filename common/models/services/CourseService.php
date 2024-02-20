<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Author;
use common\models\entity\Course;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\ConflictHttpException;

class CourseService extends Course
{
    /**
     * @param $title
     * @param $description
     * @param $status
     * @param $categoryId
     * @param $authorId
     * @param $userId
     * @return CourseService
     * @throws ConflictHttpException
     */
    public static function createCourse($title, $description, $status, $categoryId, $authorId, $photo, $userId)
    {
        $course = new self();
        $course->title = $title;
        $course->description = $description;
        $course->created_user = $userId;
        $course->category_id = $categoryId;
        $course->author_id = $authorId;
        $course->photo = $photo;
        if ($status == 'ACTIVE')
            $course->status = self::STATUS_ACTIVE;
        else
            $course->status = self::STATUS_NOT_ACTIVE;

        if (!$course->save())
            RequestHelper::exceptionModel($course);

        return $course;
    }

    /**
     * @param $id
     * @param $title
     * @param $description
     * @param $status
     * @param $categoryId
     * @param $authorId
     * @return CourseService|null
     * @throws ConflictHttpException
     */
    public static function updateCourse($id, $title = null, $description = null, $status = null, $categoryId = null, $authorId = null, $photo = null)
    {
        $course = self::findOne($id);

        if (!$course)
            RequestHelper::exception();

        if ($title)
            $course->title = $title;

        if ($description)
            $course->description = $description;

        if ($categoryId)
            $course->category_id = $categoryId;

        if ($authorId)
            $course->author_id = $authorId;

        if ($photo)
            $course->photo = $photo;

        if ($status) {
            if ($status == 'ACTIVE')
                $course->status = self::STATUS_ACTIVE;
            else
                $course->status = self::STATUS_NOT_ACTIVE;
        }

        if (!$course->save())
            RequestHelper::exceptionModel($course);

        return $course;
    }

    /**
     * @param $id
     * @return CourseService
     * @throws ConflictHttpException
     */
    public static function viewCourse($id)
    {
        return ($model = self::findOne(['id' => $id])) ? $model : RequestHelper::exception();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function listCourse()
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->orderBy('position')
            ->all();
    }

    /**
     * @param $courseId
     * @param $categoryId
     * @param $authorId
     * @return Course
     * @throws Exception
     */
    public static function findSimilar($courseId, $categoryId, $authorId)
    {
        $connection = Yii::$app->getDb();
        $courses = $connection->createCommand(
            'SELECT course.* FROM course, random_seed rs WHERE (course.category_id=' . $categoryId . ' OR course.author_id=' . $authorId . ') AND course.status=10 AND course.id=(rs.id+' . rand(0, 500) . ') ORDER BY rs.random_seed LIMIT 10'
        )->queryAll();

        return $courses;
    }

    public static function findByName($title)
    {
        $authors = Author::find()
            ->where(['like', 'fio', trim($title)])
            ->all();

        $query1 = self::find()
            ->where(['like', 'title', trim($title)])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->all();

        $query1Array = ArrayHelper::map($query1, 'id', 'id');
        $query1ArrayIds = implode(',', $query1Array);

        $query2 = self::find()
            ->where(['like', 'description', trim($title)]);
        if ($query1Array)
            $query2 = $query2->andWhere('id NOT IN (' . $query1ArrayIds . ')');

        $query2 = $query2->all();

        if ($authors) {
            $query2Array = ArrayHelper::map($query2, 'id', 'id');
            $query2ArrayIds = implode(',', $query2Array);

            $productArray = ArrayHelper::map($authors, 'id', 'id');
            $attributeIds = implode(',', $productArray);
            $query3 = self::find()
                ->where('author_id IN (' . $attributeIds . ')');
            if ($query1Array)
                $query3 = $query3->andWhere('id NOT IN (' . $query1ArrayIds . ')');

            if ($query2Array)
                $query3 = $query3->andWhere('id NOT IN (' . $query2ArrayIds . ')');

            $query3 = $query3->all();

            return array_merge($query1, $query2, $query3);
        }

        return array_merge($query1, $query2);
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