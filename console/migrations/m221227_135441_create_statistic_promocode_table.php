<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistic_promocode}}`.
 */
class m221227_135441_create_statistic_promocode_table extends Migration
{
    public $tableName = '{{%statistic_promocode}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'promo' => $this->text(),
            'count' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnTable($this->tableName, 'Статистика');
        $this->addCommentOnColumn($this->tableName, 'date', 'Дата');
        $this->addCommentOnColumn($this->tableName, 'promo', 'Промокоды');
        $this->addCommentOnColumn($this->tableName, 'count', 'Количество');
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
