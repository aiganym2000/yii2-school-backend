<?php

use yii\db\Migration;

/**
 * Class m220815_072436_delete_username_column_from_user_table
 */
class m220815_072436_delete_username_column_from_user_table extends Migration
{
    public $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn($this->tableName, 'username', $this->string()->unique());
    }
}
