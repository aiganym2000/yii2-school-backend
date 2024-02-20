<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%timing}}`.
 */
class m221021_085414_create_timing_table extends Migration
{
    public $tableName = '{{%timing}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'lesson_id' => $this->integer()->notNull(),
            'time' => $this->string()->notNull()
        ]);

        $this->addForeignKey('fk-user_id-timing', $this->tableName, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-lesson_id-timing', $this->tableName, 'lesson_id', '{{%lesson}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
