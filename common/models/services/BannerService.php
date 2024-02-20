<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Banner;
use yii\behaviors\TimestampBehavior;

class BannerService extends Banner
{
    public static function createBanner($title, $path, $size, $position, $zone, $url, $status, $publishedAt, $userId)
    {
        $banner = new self();
        $banner->title = $title;
        $banner->path = $path;
        $banner->size = $size;
        $banner->position = $position;
        $banner->zone = $zone;
        $banner->url = $url;
        $banner->published_at = $publishedAt;
        $banner->created_user_id = $userId;

        if ($status == 'ACTIVE')
            $banner->status = self::STATUS_ACTIVE;
        else
            $banner->status = self::STATUS_NOT_ACTIVE;

        if (!$banner->save())
            RequestHelper::exceptionModel($banner);

        return $banner;
    }

    public static function updateBanner($id, $title = null, $path = null, $size = null, $position = null, $zone = null, $url = null, $status = null, $publishedAt = null)
    {
        $banner = self::findOne($id);

        if (!$banner)
            RequestHelper::exception();

        if ($title)
            $banner->title = $title;

        if ($path)
            $banner->path = $path;

        if ($size)
            $banner->size = $size;

        if ($position)
            $banner->position = $position;

        if ($zone)
            $banner->zone = $zone;

        if ($url)
            $banner->url = $url;

        if ($publishedAt)
            $banner->published_at = $publishedAt;

        if ($status) {
            if ($status == 'ACTIVE')
                $banner->status = self::STATUS_ACTIVE;
            else
                $banner->status = self::STATUS_NOT_ACTIVE;
        }

        if (!$banner->save())
            RequestHelper::exceptionModel($banner);

        return $banner;
    }

    public static function viewBanner($id)
    {
        return ($model = self::findOne(['id' => $id])) ? $model : RequestHelper::exception();
    }

    public static function listBanner()
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