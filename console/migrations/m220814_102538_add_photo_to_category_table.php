<?php

use yii\db\Migration;

/**
 * Class m220814_102538_add_photo_to_category_table
 */
class m220814_102538_add_photo_to_category_table extends Migration
{
    public $tableName = '{{%category}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'photo', $this->string());
        $this->addCommentOnColumn($this->tableName, 'photo', 'Фото');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'photo');
    }
}
