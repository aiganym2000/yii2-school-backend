<?php

use yii\db\Migration;

/**
 * Class m220927_145615_add_theme_to_corporate_training_table
 */
class m220927_145615_add_theme_to_corporate_training_table extends Migration
{
    public $tableName = '{{%corporate_training}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'theme', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'theme');
    }
}
