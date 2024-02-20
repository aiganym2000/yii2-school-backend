<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report}}`.
 */
class m220802_091808_create_report_table extends Migration
{
    public $tableName = '{{%report}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'status' => $this->tinyInteger()->defaultValue(1)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->addCommentOnTable($this->tableName, 'СМС');
        $this->addCommentOnColumn($this->tableName, 'email', 'Email');
        $this->addCommentOnColumn($this->tableName, 'code', 'Код');
        $this->addCommentOnColumn($this->tableName, 'status', 'Статус');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Дата создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Дата изменения');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
