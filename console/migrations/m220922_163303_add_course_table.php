<?php

use yii\db\Migration;

/**
 * Class m220922_163303_add_course_table
 */
class m220922_163303_add_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'price_photo', $this->string());
        $this->addCommentOnColumn($this->tableName, 'price_photo', 'Изображение с ценой');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'price_photo');
    }
}
