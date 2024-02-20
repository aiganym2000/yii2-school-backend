<?php

use yii\db\Migration;

/**
 * Class m220814_075302_add_email_to_corporate_training
 */
class m220814_075302_add_email_to_corporate_training extends Migration
{
    public $tableName = '{{%corporate_training}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'email', $this->string());

        $this->addCommentOnColumn($this->tableName, 'email', 'Электронная почта');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'email');
    }
}
