<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%course}}`.
 */
class m220918_112131_add_columns_to_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'time', $this->string());
        $this->addCommentOnColumn($this->tableName, 'time', 'Время');
        $this->addColumn('{{%lesson}}', 'time', $this->string());
        $this->addCommentOnColumn('{{%lesson}}', 'time', 'Время');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%lesson}}', 'time');
        $this->dropColumn($this->tableName, 'time');
    }
}
