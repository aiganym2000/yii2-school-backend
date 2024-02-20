<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m220814_074055_create_transaction_table extends Migration
{
    public $tableName = '{{%transaction}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'amount' => $this->money(10, 2)->notNull(),
            'payment_type' => $this->smallInteger()->notNull(),
            'pay_id' => $this->string(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->addCommentOnTable($this->tableName, 'Транзакции');
        $this->addCommentOnColumn($this->tableName, 'amount', 'Цена');
        $this->addCommentOnColumn($this->tableName, 'payment_type', 'Способ оплаты');
        $this->addCommentOnColumn($this->tableName, 'pay_id', 'Идентификатор транзакции в cloud payments');
        $this->addCommentOnColumn($this->tableName, 'status', 'Статус');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Время создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Время обновления');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
