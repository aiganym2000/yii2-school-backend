<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%corporate_training}}`.
 */
class m220809_073142_create_corporate_training_table extends Migration
{
    public $tableName = '{{%corporate_training}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnTable($this->tableName, 'Корпоративное обучение');
        $this->addCommentOnColumn($this->tableName, 'name', 'Имя');
        $this->addCommentOnColumn($this->tableName, 'phone', 'Телефон');
        $this->addCommentOnColumn($this->tableName, 'text', 'Текст');
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
