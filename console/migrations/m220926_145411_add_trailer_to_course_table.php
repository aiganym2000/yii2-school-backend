<?php

use yii\db\Migration;

/**
 * Class m220926_145411_add_trailer_to_course_table
 */
class m220926_145411_add_trailer_to_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'trailer', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'trailer');
    }
}
