<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_lessons}}`.
 */
class m220928_051620_add_passed_column_to_user_lessons_table extends Migration
{
    public $tableName = '{{%user_lessons}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'passed', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'passed');
    }
}
