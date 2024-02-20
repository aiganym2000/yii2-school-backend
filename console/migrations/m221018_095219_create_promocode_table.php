<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%promocode}}`.
 */
class m221018_095219_create_promocode_table extends Migration
{
    public $tableName = '{{%promocode}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'promo' => $this->string()->unique(),
            'percent' => $this->tinyInteger(3),
            'status' => $this->tinyInteger()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnTable($this->tableName, 'Промо сертификаты');
        $this->addCommentOnColumn($this->tableName, 'promo', 'Промо Код');
        $this->addCommentOnColumn($this->tableName, 'percent', 'Сумма сертификата');
        $this->addCommentOnColumn($this->tableName, 'status', 'Статус');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Дата создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Дата обновления');

        $this->addColumn('{{%user}}', 'promocode_id', $this->integer());
        $this->addForeignKey('fk-promocode_id-user', '{{%user}}', 'promocode_id', '{{%promocode}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-promocode_id-user', '{{%user}}');
        $this->dropColumn('{{%user}}', 'promocode_id');

        $this->dropTable($this->tableName);
    }
}
