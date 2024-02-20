<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%course}}`.
 */
class m220719_082409_create_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'author_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-created_user-course', $this->tableName, 'created_user', '{{%user}}', 'id');
        $this->addForeignKey('fk-author_id-course', $this->tableName, 'author_id', '{{%author}}', 'id');
        $this->addForeignKey('fk-category_id-course', $this->tableName, 'category_id', '{{%category}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Курс');
        $this->addCommentOnColumn($this->tableName, 'title', 'Название');
        $this->addCommentOnColumn($this->tableName, 'description', 'Описание');
        $this->addCommentOnColumn($this->tableName, 'author_id', 'Создан пользователем');
        $this->addCommentOnColumn($this->tableName, 'category_id', 'Создан пользователем');
        $this->addCommentOnColumn($this->tableName, 'created_user', 'Создан пользователем');
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
