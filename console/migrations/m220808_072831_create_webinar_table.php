<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%webinar}}`.
 */
class m220808_072831_create_webinar_table extends Migration
{
    public $tableName = '{{%webinar}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'link' => $this->string()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'created_user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-created_user-webinar', $this->tableName, 'created_user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-course_id-webinar', $this->tableName, 'course_id', '{{%course}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Вебинар');
        $this->addCommentOnColumn($this->tableName, 'link', 'Ссылка');
        $this->addCommentOnColumn($this->tableName, 'date', 'Дата');
        $this->addCommentOnColumn($this->tableName, 'course_id', 'Курс');
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
