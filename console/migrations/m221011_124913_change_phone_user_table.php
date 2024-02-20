<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%room}}`.
 */
class m221011_124913_change_phone_user_table extends Migration
{
    public $tableName = '{{%user}}';

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
