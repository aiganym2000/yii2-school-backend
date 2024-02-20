<?php

namespace common\models\helpers;


use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

class SortableGridBehavior extends \himiklab\sortablegrid\SortableGridBehavior
{
    public $attributeName;

    public function beforeInsert()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if (!$model->hasAttribute($this->sortableAttribute)) {
            throw new InvalidConfigException("Invalid sortable attribute `{$this->sortableAttribute}`.");
        }

        $cookies = Yii::$app->request->cookies;
        $query = $model::find()
            ->where(['{{' . $this->attributeName . '}}' => $cookies->get('sortableId')]);
        if (is_callable($this->scope)) {
            call_user_func($this->scope, $query);
        }

        /* Override model alias if defined in the model's class */
        $query->from([$model::tableName() => $model::tableName()]);

        $maxOrder = $query->max('{{' . trim($model::tableName(), '{}') . '}}.[[' . $this->sortableAttribute . ']]');
        $model->{$this->sortableAttribute} = $maxOrder + 1;
    }
}