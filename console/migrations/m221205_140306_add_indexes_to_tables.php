<?php

use yii\db\Migration;

/**
 * Class m221205_140306_add_indexes_to_tables
 */
class m221205_140306_add_indexes_to_tables extends Migration
{
    public $tableName = '{{%question}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_course_id_question',
            $this->tableName,
            ['course_id'],
            false);

        $this->createIndex('idx_course_id_user_lessons',
            '{{%user_lessons}}',
            ['course_id', 'user_id'],
            false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-course_id-question', $this->tableName);
        $this->dropIndex('idx_course_id_question',
            $this->tableName);;
        $this->addForeignKey('fk-course_id-question', $this->tableName, 'course_id', '{{%course}}', 'id');

        $this->dropForeignKey('fk-user_lessons-course_id', '{{%user_lessons}}');
        $this->dropIndex('idx_course_id_user_lessons',
            '{{%user_lessons}}');
        $this->addForeignKey('fk-user_lessons-course_id', '{{%user_lessons}}', 'course_id', '{{%course}}', 'id');
    }
}
