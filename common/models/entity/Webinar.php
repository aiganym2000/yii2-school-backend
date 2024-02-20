<?php

namespace common\models\entity;

use vova07\fileapi\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "webinar".
 *
 * @property int $id
 * @property string $link Ссылка
 * @property string $date Дата
 * @property int $course_id Курс
 * @property string $img Изображение
 * @property string $title Описание
 * @property int $status Статус
 * @property string $price
 * @property string $description
 * @property int $created_user_id Создан пользователем
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property Course $course
 * @property User $createdUser
 */
class Webinar extends BaseEntity
{
    const IMG_PATH = 'webinar';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'webinar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link', 'date', 'course_id', 'status', 'price', 'img'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
            [['date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['course_id', 'status', 'created_user_id'], 'integer'],
            [['link', 'img', 'title', 'description'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['created_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_user_id' => 'id']],
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
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'img' => [
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

    public function fields()
    {
        return [
            'id',
            //'link',
            'date',
            'price',
            'description',
            'img' => function (self $model) {
                return $model->getImgUrl();
            },
            'title',
            'course_id' => function (self $model) {
                return ($table = $model->getCourse()->one()) ? $table : '';
            },
            'created_user_id' => function (self $model) {
                return ($table = $model->getCreatedUser()->one()) ? $table->getData() : '';
            },
            'status' => function (self $model) {
                return $model->getStatusLabel();
            },
            'cart_status' => function (self $model) {
                $cartStatus = false;
                if ($userId = Yii::$app->user->id) {
                    $user = User::findOne($userId);
                    $cart = json_decode($user->cart, true);
                    if ($cart && in_array($model->id . '-1', $cart))
                        $cartStatus = true;
                }

                return $cartStatus ? 1 : 0;
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
        return $this->img ? $this->getImgPath() . $this->img : $this->getImgPath() . 'default.png';
    }

    /**
     * @return ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'link' => Yii::t('app', 'LINK'),
            'img' => Yii::t('app', 'IMG'),
            'title' => Yii::t('app', 'TITLE'),
            'date' => Yii::t('app', 'DATE'),
            'course_id' => Yii::t('app', 'COURSE_ID'),
            'status' => Yii::t('app', 'STATUS'),
            'price' => Yii::t('app', 'PRICE'),
            'description' => Yii::t('app', 'DESCRIPTION'),
            'created_user_id' => Yii::t('app', 'CREATED_USER'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $pCourses = PurchasedWebinar::findAll(['webinar_id' => $id]);
        foreach ($pCourses as $pCourse) {
            $pCourse->delete();
        }

        return parent::beforeDelete();
    }
}
