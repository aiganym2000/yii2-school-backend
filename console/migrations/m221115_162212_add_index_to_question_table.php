<?php

use yii\db\Migration;

/**
 * Class m221115_162212_add_index_to_question_table
 */
class m221115_162212_add_index_to_question_table extends Migration
{
    public $tableName = '{{%user_answer}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_unique_user_id',
            $this->tableName,
            ['user_id', 'question_id'],
            false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_unique_user_id',
        $this->tableName);
    }
}
