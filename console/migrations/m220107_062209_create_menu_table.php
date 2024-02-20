<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu}}`.
 */
class m220107_062209_create_menu_table extends Migration
{
    public $tableName = "{{%menu}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'url' => $this->string(),
            'icon' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(null),
            'created_at' => $this->dateTime()->notNull()->defaultValue(date("Y-m-d H:i:s")),
            'updated_at' => $this->dateTime()->notNull()->defaultValue(date("Y-m-d H:i:s")),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
