<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_lessons}}`.
 */
class m220922_112424_create_user_lessons_table extends Migration
{
    public $tableName = '{{%user_lessons}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'lesson_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'score' => $this->integer()->notNull(),
            'question_count' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user_lessons-lesson_id', $this->tableName, 'lesson_id', '{{%lesson}}', 'id');
        $this->addForeignKey('fk-user_lessons-user_id', $this->tableName, 'user_id', '{{%user}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Пройденные лекции');
        $this->addCommentOnColumn($this->tableName, 'lesson_id', 'Уроки');
        $this->addCommentOnColumn($this->tableName, 'user_id', 'Пользователь');
        $this->addCommentOnColumn($this->tableName, 'score', 'Правильно отвеченные вопросы');
        $this->addCommentOnColumn($this->tableName, 'question_count', 'Общие вопросы');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Дата создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Дата обновления');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
