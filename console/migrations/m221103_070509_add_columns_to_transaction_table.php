<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%transaction}}`.
 */
class m221103_070509_add_columns_to_transaction_table extends Migration
{
    public $tableName = '{{%transaction}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'cart', 'LONGTEXT');
        $this->addColumn($this->tableName, 'promocode_id', $this->integer());
        $this->addColumn($this->tableName, 'user_id', $this->integer());
        $this->addCommentOnColumn($this->tableName, 'cart', 'Корзина');
        $this->addCommentOnColumn($this->tableName, 'promocode_id', 'Промокод');
        $this->addCommentOnColumn($this->tableName, 'user_id', 'Пользователь');

        $this->addForeignKey('fk-user_id-transaction', $this->tableName, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-promocode_id-transaction', $this->tableName, 'promocode_id', '{{%promocode}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_id-transaction', $this->tableName);
        $this->dropForeignKey('fk-promocode_id-transaction', $this->tableName);

        $this->dropColumn($this->tableName, 'cart');
        $this->dropColumn($this->tableName, 'promocode_id');
        $this->dropColumn($this->tableName, 'user_id');
    }
}
