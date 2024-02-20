<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lesson}}`.
 */
class m220801_071812_create_lesson_table extends Migration
{
    public $tableName = '{{%lesson}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'video' => $this->string()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'created_user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);


        $this->addForeignKey('fk-created_user-lesson', $this->tableName, 'created_user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-course_id-lesson', $this->tableName, 'course_id', '{{%course}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Урок');
        $this->addCommentOnColumn($this->tableName, 'title', 'Название');
        $this->addCommentOnColumn($this->tableName, 'description', 'Описание');
        $this->addCommentOnColumn($this->tableName, 'video', 'Видео');
        $this->addCommentOnColumn($this->tableName, 'course_id', 'Курс');
        $this->addCommentOnColumn($this->tableName, 'position', 'Позиция');
        $this->addCommentOnColumn($this->tableName, 'created_user_id', 'Создан пользователем');
        $this->addCommentOnColumn($this->tableName, 'status', 'Статус');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Время создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Время обновления');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
