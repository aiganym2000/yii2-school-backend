<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 */
class m220815_074348_create_notification_table extends Migration
{
    public $tableName = '{{%notification}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'user_id' => $this->integer()->null(),
            'created_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-notification-user_id', $this->tableName, 'user_id', '{{%user}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Уведомления в кабинете');
        $this->addCommentOnColumn($this->tableName, 'title', 'Название');
        $this->addCommentOnColumn($this->tableName, 'description', 'Описание');
        $this->addCommentOnColumn($this->tableName, 'user_id', 'Пользователь');
        $this->addCommentOnColumn($this->tableName, 'status', 'Статус');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Дата создания');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
