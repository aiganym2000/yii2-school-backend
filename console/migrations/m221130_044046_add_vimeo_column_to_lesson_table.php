<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%lesson}}`.
 */
class m221130_044046_add_vimeo_column_to_lesson_table extends Migration
{
    public $tableName = '{{%lesson}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'vimeo', $this->string());
        $this->addColumn('{{%author}}', 'vimeo', $this->string());
        $this->addColumn('{{%course}}', 'vimeo', $this->string());
        $this->addCommentOnColumn('{{%author}}', 'vimeo', 'Поле для вимео');
        $this->addCommentOnColumn('{{%course}}', 'vimeo', 'Поле для вимео');
        $this->addCommentOnColumn($this->tableName, 'vimeo', 'Поле для вимео');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%author}}', 'vimeo');
        $this->dropColumn('{{%course}}', 'vimeo');
        $this->dropColumn($this->tableName, 'vimeo');
    }
}
