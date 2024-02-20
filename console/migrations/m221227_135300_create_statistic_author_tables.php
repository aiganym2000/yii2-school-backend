<?php

use yii\db\Migration;

/**
 * Class m221227_135300_add_statistic_tables
 */
class m221227_135300_create_statistic_author_tables extends Migration
{
    public $tableName = '{{%statistic_author}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'author_id' => $this->integer(),
            'count' => $this->integer(),
            'sum' => $this->money(10, 2),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        $this->addForeignKey('fk-author_id-statistic_author', $this->tableName, 'author_id', '{{%author}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Статистика');
        $this->addCommentOnColumn($this->tableName, 'date', 'Дата');
        $this->addCommentOnColumn($this->tableName, 'author_id', 'Спикеры');
        $this->addCommentOnColumn($this->tableName, 'count', 'Количество');
        $this->addCommentOnColumn($this->tableName, 'sum', 'Сумма');
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
