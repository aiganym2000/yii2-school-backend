<?php

use yii\db\Migration;

/**
 * Class m221021_082830_change_corporate_training_table
 */
class m221021_082830_change_corporate_training_table extends Migration
{
    public $tableName = '{{%corporate_training}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'phone', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'phone', $this->string()->notNull());
    }
}
