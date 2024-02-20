<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Author;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\ConflictHttpException;

class AuthorService extends Author
{
    /**
     * @param $fio
     * @param $status
     * @param $description
     * @param $photo
     * @param $userId
     * @return AuthorService
     * @throws ConflictHttpException
     */
    public static function createAuthor($fio, $status, $description, $photo, $userId)
    {
        $author = new self();
        $author->fio = $fio;
        $author->description = $description;
        $author->photo = $photo;
        $author->created_user = $userId;
        if ($status == 'ACTIVE')
            $author->status = self::STATUS_ACTIVE;
        else
            $author->status = self::STATUS_NOT_ACTIVE;

        if (!$author->save())
            RequestHelper::exceptionModel($author);

        return $author;
    }

    /**
     * @param $id
     * @param $fio
     * @param $status
     * @param $description
     * @param $photo
     * @return AuthorService|null
     * @throws ConflictHttpException
     */
    public static function updateAuthor($id, $fio = null, $status = null, $description = null, $photo = null)
    {
        $author = self::findOne($id);

        if (!$author)
            RequestHelper::exception();

        if ($fio)
            $author->fio = $fio;

        if ($description)
            $author->description = $description;

        if ($photo)
            $author->photo = $photo;

        if ($status) {
            if ($status == 'ACTIVE')
                $author->status = self::STATUS_ACTIVE;
            else
                $author->status = self::STATUS_NOT_ACTIVE;
        }

        if (!$author->save())
            RequestHelper::exceptionModel($author);

        return $author;
    }

    /**
     * @param $id
     * @return AuthorService
     * @throws ConflictHttpException
     */
    public static function viewAuthor($id)
    {
        return ($model = self::findOne(['id' => $id])) ? $model : RequestHelper::exception();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function listAuthor()
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