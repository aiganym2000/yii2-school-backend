<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%course}}`.
 */
class m220802_074635_add_price_column_to_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'price', $this->money(10, 2));

        $this->addCommentOnColumn($this->tableName, 'price', 'Цена');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'price');
    }
}
