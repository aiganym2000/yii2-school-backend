<?php

use yii\db\Migration;

/**
 * Class m221101_115327_add_short_description_to_course_table
 */
class m221101_115327_add_short_description_to_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'short_description', $this->string());
        $this->addCommentOnColumn($this->tableName, 'short_description', 'Краткое описание');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'short_description');
    }
}
