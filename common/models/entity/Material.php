<?php

namespace common\models\entity;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $img
 * @property int $status
 * @property string $slug
 * @property string $meta_description
 * @property string $meta_keywords
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 */
class Material extends BaseEntity
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'material';
    }


    public function behaviors()
    {
        return array_merge(parent::rules(), [
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
                'slugAttribute' => 'slug',
                'attribute' => ['id', 'title'],
                // optional params
                'ensureUnique' => true,
                'replacement' => '-',
                'lowercase' => true,
                'immutable' => false,
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'self'],
            [['content'], 'string'],
            [['status'], 'integer'],
            [['title'], 'required'],
            [['title', 'img', 'slug', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
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
            'content' => Yii::t('app', 'CONTENT'),
            'img' => Yii::t('app', 'IMG'),
            'status' => Yii::t('app', 'STATUS'),
            'slug' => Yii::t('app', 'SLUG'),
            'meta_description' => Yii::t('app', 'META_DESCRIPTION'),
            'meta_keywords' => Yii::t('app', 'META_KEYWORDS'),
            'created_at' => Yii::t('app', 'CREATED_AT'),
            'updated_at' => Yii::t('app', 'UPDATED_AT'),
        ];
    }
}
