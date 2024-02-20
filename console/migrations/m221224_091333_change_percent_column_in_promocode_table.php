<?php

use yii\db\Migration;

/**
 * Class m221224_091333_change_percent_column_in_promocode_table
 */
class m221224_091333_change_percent_column_in_promocode_table extends Migration
{
    public $tableName = '{{%promocode}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'percent', $this->decimal(8, 5));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'percent', $this->tinyInteger(3));
    }
}
