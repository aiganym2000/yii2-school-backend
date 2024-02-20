<?php

namespace common\models\entity;

use himiklab\sortablegrid\SortableGridBehavior;
use vova07\fileapi\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "course".
 *
 * @property int $id
 * @property string $title Название
 * @property string $description Описание
 * @property string $short_description Описание
 * @property string $price Цена
 * @property string $photo Фото
 * @property string $price_photo Фото
 * @property string $trailer
 * @property string $vimeo Вимео
 * @property string $apple_id
 * @property int $position Позиция
 * @property string $time Время
 * @property int $author_id Создан пользователем
 * @property int $category_id Создан пользователем
 * @property int $created_user Создан пользователем
 * @property int $status Статус
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 *
 * @property Author $author
 * @property Category $category
 * @property User $createdUser
 */
class Course extends BaseEntity
{
    const IMG_PATH = 'course';
    const VIDEO_PATH = 'video';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course';
    }

    public function beforeDelete()
    {
        $id = $this->id;

        $lessons = Lesson::findAll(['course_id' => $id]);
        foreach ($lessons as $lesson) {
            $lesson->delete();
        }
        $pCourses = PurchasedCourse::findAll(['course_id' => $id]);
        foreach ($pCourses as $pCourse) {
            $pCourse->delete();
        }
        $webinars = Webinar::findAll(['course_id' => $id]);
        foreach ($webinars as $webinar) {
            $webinar->delete();
        }
        $questions = Question::findAll(['course_id' => $id]);
        foreach ($questions as $question) {
            $question->delete();
        }
        $results = UserLessons::findAll(['course_id' => $id]);
        foreach ($results as $result) {
            $result->delete();
        }

        $courses = Course::find()
            ->andWhere(['!=', 'id', $this->id])
            ->orderBy('position')
            ->all();
        $i = 1;
        foreach ($courses as $course) {
            $course->position = $i;
            $course->save();
            $i++;
        }

        return parent::beforeDelete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id', 'category_id', 'status', 'photo', 'price_photo'], 'required'],
            [['description', 'trailer', 'vimeo', 'apple_id'], 'string'],
            [['price', 'time'], 'number'],
            [['author_id', 'category_id', 'created_user', 'position', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 100],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['created_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_user' => 'id']],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'description',
            'short_description',
            'price',
            'position',
            'vimeo',
            'apple_id',
            'author_id' => function (self $model) {
                return ($table = $model->getAuthor()->one()) ? $table : '';
            },
            'category_id' => function (self $model) {
                return ($table = $model->getCategory()->one()) ? $table : '';
            },
            'photo' => function (self $model) {
                return $model->getImgUrl();
            },
            'price_photo' => function (self $model) {
                return $model->getPriceImgUrl();
            },
            'created_user' => function (self $model) {
                return ($table = $model->getCreatedUser()->one()) ? $table->getData() : '';
            },
            'lessons' => function (self $model) {
                $lessons = Lesson::find()->where(['course_id' => $model->id])->orderBy('position')->all();
                $lessonArray = [];
                foreach ($lessons as $lesson) {
                    $lessonArray[] = [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'time' => floor($lesson->time / 3600) . gmdate(":i:s", $lesson->time % 3600),
                        'description' => $lesson->description,
                    ];
                }

                return $lessonArray;
            },
            'count' => function (self $model) {
                return Lesson::find()
                    ->where(['course_id' => $model->id])
                    ->count();//todo
            },
            'time' => function (self $model) {
                return floor($model->time / 3600) . gmdate(":i:s", $model->time % 3600);
            },
            'buyed' => function (self $model) {
                $userId = Yii::$app->user->id;
                if (!$userId)
                    return 0;
                $user = User::findOne($userId);
                if ($user && $user->full_access == User::ACCESS_ON)
                    return 1;

                return PurchasedCourse::findOne(['course_id' => $model->id, 'user_id' => Yii::$app->user->id]) ? 1 : 0;
            },
            'answered_count' => function (self $model) {
                $questions = Question::findAll(['course_id' => $model->id]);
                $productArray = ArrayHelper::map($questions, 'id', 'id');
                $attributeIds = implode(',', $productArray);
                if ($attributeIds && Yii::$app->user->id) {
                    $answered_count = UserAnswer::find()
                        ->where('question_id IN (' . $attributeIds . ')')
                        ->andWhere(['user_id' => Yii::$app->user->id])
                        ->count('DISTINCT(question_id)');
                } else {
                    $answered_count = 0;
                }
                return (int)$answered_count;
            },
            'question_count' => function (self $model) {
                return Question::find()
                    ->where(['course_id' => $model->id])
                    ->count();
            },
            'passed' => function (self $model) {
//                if ($userId = Yii::$app->user->id) {
//                    $limit = Setting::findOne(['key' => 'percent_limit']);
//                    if ($limit && (int)$limit->value <= 100)
//                        $limit = (int)$limit->value * 0.01;
//                    else
//                        $limit = 0.5;
//
//                    $questions = Question::find()->where(['course_id' => $model->id])->count();
//
//                    $userLesson = UserLessons::find()
//                        ->where(['user_id' => $userId])
//                        ->andWhere(['course_id' => $model->id])
//                        ->andWhere(['>=', 'score', $limit * $questions])
//                        ->one();//todo
//                } else {
//                    $userLesson = false;
//                }
//                return $userLesson ? 1 : 0;
                return 0;
            },
            'cart_status' => function (self $model) {
                $cartStatus = false;
                if ($userId = Yii::$app->user->id) {
                    $user = User::findOne($userId);
                    $cart = json_decode($user->cart, true);
                    if ($cart && in_array($model->id . '-0', $cart))
                        $cartStatus = true;
                }

                return $cartStatus ? 1 : 0;
            },
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return string
     */
    public function getImgUrl()
    {
        return $this->photo ? $this->getImgPath() . $this->photo : $this->getImgPath() . 'default.png';
    }

    /**
     * @return string
     */
    public function getImgPath()
    {
        return Yii::$app->params['staticDomain'] . '/images/' . self::IMG_PATH . '/';
    }

    /**
     * @return string
     */
    public function getPriceImgUrl()
    {
        return $this->price_photo ? $this->getImgPath() . $this->price_photo : $this->getImgPath() . 'default.png';
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user']);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'id' => $this->id,
        ];
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->trailer ? $this->getVideoPath() . $this->trailer : '';
    }

    /**
     * @return string
     */
    public function getVideoPath()
    {
        return Yii::$app->params['staticDomain'] . '/' . self::VIDEO_PATH . '/';
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
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'position',
            ],
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'photo' => [
                        'path' => '@static/web/images/' . self::IMG_PATH . '/',
                        'tempPath' => '@static/temp/',
                        'url' => $this->getImgPath()
                    ],
                    'price_photo' => [
                        'path' => '@static/web/images/' . self::IMG_PATH . '/',
                        'tempPath' => '@static/temp/',
                        'url' => $this->getImgPath()
                    ],
                    'trailer' => [
                        'path' => '@static/web/video/',
                        'tempPath' => '@static/temp/',
                        'url' => $this->getVideoPath()
                    ],
                ]
            ],
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
            'position' => Yii::t('app', 'POSITION'),
            'description' => Yii::t('app', 'DESCRIPTION'),
            'short_description' => Yii::t('app', 'SHORT_DESCRIPTION'),
            'photo' => Yii::t('app', 'NON_PRICE_PHOTO'),
            'price_photo' => Yii::t('app', 'PRICE_PHOTO'),
            'trailer' => Yii::t('app', 'TRAILER'),
            'vimeo' => Yii::t('app', 'VIMEO'),
            'apple_id' => Yii::t('app', 'AppleID'),
            'author_id' => Yii::t('app', 'AUTHOR_ID'),
            'category_id' => Yii::t('app', 'CATEGORY_ID'),
            'created_user' => Yii::t('app', 'CREATED_USER'),
            'status' => Yii::t('app', 'STATUS'),
            'price' => Yii::t('app', 'PRICE'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
