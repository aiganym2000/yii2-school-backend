<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%author}}`.
 */
class m221215_123615_add_small_photo_column_to_author_table extends Migration
{
    public $tableName = '{{%author}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'small_photo', $this->string());
        $this->addCommentOnColumn($this->tableName, 'small_photo', 'Фото для мобилки');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'small_photo');
    }
}
