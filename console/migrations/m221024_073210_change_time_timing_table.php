<?php

use yii\db\Migration;

/**
 * Class m221024_073210_change_time_timing_table
 */
class m221024_073210_change_time_timing_table extends Migration
{
    public $tableName = '{{%timing}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'time');
        $this->addColumn($this->tableName, 'time', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'time');
        $this->addColumn($this->tableName, 'time', $this->string()->notNull());
    }
}
