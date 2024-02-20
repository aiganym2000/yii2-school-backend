<?php

use yii\db\Migration;

/**
 * Class m230126_130606_add_apple_id_to_course_table
 */
class m230126_130606_add_apple_id_to_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'apple_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
     $this->dropColumn($this->tableName, 'apple_id');
    }
}
