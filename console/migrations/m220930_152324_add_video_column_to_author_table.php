<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%author}}`.
 */
class m220930_152324_add_video_column_to_author_table extends Migration
{
    public $tableName = '{{%author}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'video', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'video');
    }
}
