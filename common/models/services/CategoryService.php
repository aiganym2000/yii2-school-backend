<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Category;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\ConflictHttpException;

class CategoryService extends Category
{
    /**
     * @param $title
     * @param $description
     * @param $status
     * @param $userId
     * @return CategoryService
     * @throws ConflictHttpException
     */
    public static function createCategory($title, $description, $status, $photo, $userId)
    {
        $category = new self();
        $category->title = $title;
        $category->description = $description;
        $category->created_user = $userId;
        $category->photo = $photo;
        if ($status == 'ACTIVE')
            $category->status = self::STATUS_ACTIVE;
        else
            $category->status = self::STATUS_NOT_ACTIVE;

        if (!$category->save())
            RequestHelper::exceptionModel($category);

        return $category;
    }

    /**
     * @param $id
     * @param $title
     * @param $description
     * @param $status
     * @return CategoryService|null
     * @throws ConflictHttpException
     */
    public static function updateCategory($id, $title = null, $description = null, $status = null, $photo = null)
    {
        $category = self::findOne($id);

        if (!$category)
            RequestHelper::exception();

        if ($title)
            $category->title = $title;

        if ($description)
            $category->description = $description;

        if ($photo)
            $category->photo = $photo;

        if ($status) {
            if ($status == 'ACTIVE')
                $category->status = self::STATUS_ACTIVE;
            else
                $category->status = self::STATUS_NOT_ACTIVE;
        }

        if (!$category->save())
            RequestHelper::exceptionModel($category);

        return $category;
    }

    /**
     * @param $id
     * @return CategoryService|null
     */
    public static function viewCategory($id)
    {
        return ($model = self::findOne(['id' => $id])) ? $model : RequestHelper::exception();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function listCategory()
    {
        return self::find()->all();
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