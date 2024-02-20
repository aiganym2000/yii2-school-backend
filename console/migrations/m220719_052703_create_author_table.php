<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author}}`.
 */
class m220719_052703_create_author_table extends Migration
{
    public $tableName = '{{%author}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'fio' => $this->string()->notNull(),
            'created_user' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-created_user-author', $this->tableName, 'created_user', '{{%user}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Автор');
        $this->addCommentOnColumn($this->tableName, 'fio', 'ФИО');
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
