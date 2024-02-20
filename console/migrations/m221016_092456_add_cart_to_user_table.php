<?php

use yii\db\Migration;

/**
 * Class m221016_092456_add_cart_to_user_table
 */
class m221016_092456_add_cart_to_user_table extends Migration
{
    public $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'cart', 'LONGTEXT');
        $this->addColumn('{{%webinar}}', 'price', $this->money(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'cart');
        $this->dropColumn('{{%webinar}}', 'price');
    }
}
