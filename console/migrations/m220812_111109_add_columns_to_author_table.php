<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%author}}`.
 */
class m220812_111109_add_columns_to_author_table extends Migration
{
    public $tableName = '{{%author}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'description', $this->text());
        $this->addColumn($this->tableName, 'photo', $this->string());

        $this->addCommentOnColumn($this->tableName, 'description', 'Описание');
        $this->addCommentOnColumn($this->tableName, 'photo', 'Фото');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'description');
        $this->dropColumn($this->tableName, 'photo');
    }
}
