<?php

namespace common\models\entity;

use Exception;
use himiklab\sortablegrid\SortableGridBehavior;
use vova07\fileapi\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "banner".
 *
 * @property int $id
 * @property string $title Заголовок
 * @property string $path Путь
 * @property int $size Размер
 * @property int $position Позиция
 * @property int $status Статус
 * @property int $zone Зона
 * @property string $url Ссылка
 * @property string $published_at Дата публикации
 * @property integer $created_user_id Пользователь
 * @property string $created_at Дата создания
 * @property string $updated_at Дата изменения
 *
 * @property User $createdUser
 */
class Banner extends BaseEntity
{
    const IMG_PATH = 'banner';

    const ZONE_ONE = 1;
    const ZONE_TWO = 2;
    const ZONE_THREE = 3;
    const ZONE_FOUR = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'path', 'size', 'zone', 'status'], 'required'],
            [['size', 'position', 'status', 'zone', 'created_user_id'], 'integer'],
            [['published_at', 'created_at', 'updated_at'], 'safe'],
            [['title', 'path', 'url'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_NOT_ACTIVE, self::STATUS_DELETED]],
            ['zone', 'in', 'range' => [self::ZONE_ONE, self::ZONE_TWO, self::ZONE_THREE, self::ZONE_FOUR]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'TITLE'),
            'path' => Yii::t('app', 'PATH'),
            'size' => Yii::t('app', 'SIZE'),
            'position' => Yii::t('app', 'POSITION'),
            'status' => Yii::t('app', 'STATUS'),
            'published_at' => Yii::t('app', 'PUBLISHED_AT'),
            'created_user_id' => Yii::t('app', 'CREATED_USER'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
            'zone' => Yii::t('app', 'ZONE'),
            'url' => Yii::t('app', 'URL'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'position'
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s')
            ],
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'path' => [
                        'path' => '@static/web/images/' . self::IMG_PATH . '/',
                        'tempPath' => '@static/temp/',
                        'url' => $this->getImgPath()
                    ],
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    public function getImgPath()
    {
        return Yii::$app->params['staticDomain'] . '/images/' . self::IMG_PATH . '/';
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'title',
            'path' => function () {
                return $this->getImgUrl();
            },
            'size',
            'zone',
            'position',
            'url',
            'published_at',
            'created_user_id' => function (self $model) {
                return ($table = $model->getCreatedUser()->one()) ? $table->getData() : '';
            },
            'status' => function (self $model) {
                return $model->getStatusLabel();
            },
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->path ? $this->getImgPath() . $this->path : $this->getImgPath() . 'default.png';
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user_id']);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getZoneLabel()
    {
        return ArrayHelper::getValue(static::getZoneList(), $this->zone);
    }

    /**
     * @return array
     */
    public static function getZoneList()
    {
        return [
            self::ZONE_ONE => Yii::t('app', 'ZONE_ONE'),
            self::ZONE_TWO => Yii::t('app', 'ZONE_TWO'),
            self::ZONE_THREE => Yii::t('app', 'ZONE_THREE'),
            self::ZONE_FOUR => Yii::t('app', 'ZONE_FOUR'),
        ];
    }
}
