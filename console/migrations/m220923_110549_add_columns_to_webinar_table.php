<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%webinar}}`.
 */
class m220923_110549_add_columns_to_webinar_table extends Migration
{
    public $tableName = '{{%webinar}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'img', $this->string());
        $this->addColumn($this->tableName, 'title', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'img');
        $this->dropColumn($this->tableName, 'title');
    }
}
