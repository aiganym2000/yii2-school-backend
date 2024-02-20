<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%setting}}`.
 */
class m220812_131155_create_setting_table extends Migration
{
    public $tableName = "{{%setting}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'key' => $this->string()->notNull(),
            'value' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->addCommentOnTable($this->tableName, 'Настройки');
        $this->addCommentOnColumn($this->tableName, 'title', 'Название');
        $this->addCommentOnColumn($this->tableName, 'key', 'Ключ');
        $this->addCommentOnColumn($this->tableName, 'value', 'Значение');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Дата создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Дата обновления');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
