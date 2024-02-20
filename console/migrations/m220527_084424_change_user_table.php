<?php

use yii\db\Migration;

/**
 * Class m220527_084424_change_user_table
 */
class m220527_084424_change_user_table extends Migration
{
    public $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'f_token', $this->string());
        $this->addColumn($this->tableName, 'phone', $this->string()->notNull());

        $this->addCommentOnColumn($this->tableName, 'f_token', 'Токен firebase');
        $this->addCommentOnColumn($this->tableName, 'phone', 'Телефон');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'f_token');
        $this->dropColumn($this->tableName, 'phone');
    }
}
