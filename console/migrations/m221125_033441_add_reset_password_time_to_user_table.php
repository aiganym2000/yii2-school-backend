<?php

use yii\db\Migration;

/**
 * Class m221125_033441_add_reset_password_time_to_user_table
 */
class m221125_033441_add_reset_password_time_to_user_table extends Migration
{
    public $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'reset_password_time', $this->dateTime());
        $this->addCommentOnColumn($this->tableName, 'reset_password_time', 'Время последнего сброса пароля');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'reset_password_time');
    }
}
