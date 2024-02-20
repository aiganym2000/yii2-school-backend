<?php

use yii\db\Migration;

/**
 * Class m221004_130328_redo_question_table
 */
class m221004_130328_redo_question_table extends Migration
{
    public $tableName = '{{%question}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%user_answer}}');
        $this->dropTable('{{%user_result}}');
        $this->dropTable($this->tableName);

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'position' => $this->integer()->notNull(),
            'type' => $this->tinyInteger()->notNull(),
            'answer' => $this->text()->notNull(),
            'created_user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-created_user-question', $this->tableName, 'created_user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-course_id-question', $this->tableName, 'course_id', '{{%course}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Домашние задания');
        $this->addCommentOnColumn($this->tableName, 'course_id', 'Курс');
        $this->addCommentOnColumn($this->tableName, 'position', 'Позиция');
        $this->addCommentOnColumn($this->tableName, 'type', 'Тип');
        $this->addCommentOnColumn($this->tableName, 'answer', 'Ответ');
        $this->addCommentOnColumn($this->tableName, 'created_user_id', 'Создан пользователем');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Время создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Время обновления');

        $this->createTable('{{%user_answer}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'answer' => $this->text()->notNull(),
            'right' => $this->boolean()->notNull(),
            'created_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user_id-user_answer', '{{%user_answer}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-question_id-user_answer', '{{%user_answer}}', 'question_id', $this->tableName, 'id');

        $this->addCommentOnTable('{{%user_answer}}', 'Ответы пользователей');
        $this->addCommentOnColumn('{{%user_answer}}', 'user_id', 'Пользователь');
        $this->addCommentOnColumn('{{%user_answer}}', 'question_id', 'Домашнее задание');
        $this->addCommentOnColumn('{{%user_answer}}', 'answer', 'Ответ');
        $this->addCommentOnColumn('{{%user_answer}}', 'right', 'Правильно');
        $this->addCommentOnColumn('{{%user_answer}}', 'created_at', 'Время создания');

        $this->dropForeignKey('fk-user_lessons-lesson_id', '{{%user_lessons}}');
        $this->dropColumn('{{%user_lessons}}', 'lesson_id');
        $this->addColumn('{{%user_lessons}}', 'course_id', $this->integer());
        $this->addForeignKey('fk-user_lessons-course_id', '{{%user_lessons}}', 'course_id', '{{%course}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_lessons-course_id', '{{%user_lessons}}');
        $this->dropColumn('{{%user_lessons}}', 'course_id');
        $this->addColumn('{{%user_lessons}}', 'lesson_id', $this->integer());
        $this->addForeignKey('fk-user_lessons-lesson_id', '{{%user_lessons}}', 'lesson_id', '{{%lesson}}', 'id');

        $this->dropTable('{{%user_answer}}');
        $this->dropTable($this->tableName);
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'lesson_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'position' => $this->integer()->notNull(),
            'type' => $this->tinyInteger()->notNull(),
            'answer' => $this->text()->notNull(),
            'created_user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-created_user-question', $this->tableName, 'created_user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-lesson_id-question', $this->tableName, 'lesson_id', '{{%lesson}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Домашние задания');
        $this->addCommentOnColumn($this->tableName, 'lesson_id', 'Урок');
        $this->addCommentOnColumn($this->tableName, 'position', 'Позиция');
        $this->addCommentOnColumn($this->tableName, 'type', 'Тип');
        $this->addCommentOnColumn($this->tableName, 'answer', 'Ответ');
        $this->addCommentOnColumn($this->tableName, 'created_user_id', 'Создан пользователем');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Время создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Время обновления');

        $this->createTable('{{%user_answer}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'answer' => $this->text()->notNull(),
            'right' => $this->boolean()->notNull(),
            'created_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user_id-user_answer', '{{%user_answer}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-question_id-user_answer', '{{%user_answer}}', 'question_id', $this->tableName, 'id');

        $this->addCommentOnTable('{{%user_answer}}', 'Ответы пользователей');
        $this->addCommentOnColumn('{{%user_answer}}', 'user_id', 'Пользователь');
        $this->addCommentOnColumn('{{%user_answer}}', 'question_id', 'Домашнее задание');
        $this->addCommentOnColumn('{{%user_answer}}', 'answer', 'Ответ');
        $this->addCommentOnColumn('{{%user_answer}}', 'right', 'Правильно');
        $this->addCommentOnColumn('{{%user_answer}}', 'created_at', 'Время создания');

        $this->createTable('{{%user_result}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'lesson_id' => $this->integer()->notNull(),
            'score' => $this->text()->notNull(),
            'created_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user_id-user_result', '{{%user_result}}', 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-lesson_id-user_result', '{{%user_result}}', 'lesson_id', '{{%lesson}}', 'id');

        $this->addCommentOnTable('{{%user_result}}', 'Результаты пользовтелей');
        $this->addCommentOnColumn('{{%user_result}}', 'user_id', 'Пользователь');
        $this->addCommentOnColumn('{{%user_result}}', 'lesson_id', 'Урок');
        $this->addCommentOnColumn('{{%user_result}}', 'score', 'Результат');
        $this->addCommentOnColumn('{{%user_result}}', 'created_at', 'Время создания');
    }
}
