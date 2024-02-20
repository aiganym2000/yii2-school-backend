<?php

use yii\db\Migration;

/**
 * Handles the creation of table `material`.
 */
class m180825_070027_create_material_table extends Migration
{
    public $tableName = "{{%material}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
            'img'        => $this->string(),
            'status'  => $this->smallInteger()->notNull()->defaultValue(0),
            'slug'    => $this->string()->notNull(),
            'meta_description' => $this->string(),
            'meta_keywords' => $this->string(),
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
