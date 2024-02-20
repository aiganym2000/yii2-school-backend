<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistic}}`.
 */
class m221222_121107_create_statistic_table extends Migration
{
    public $tableName = '{{%statistic}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'average_check' => $this->money()->notNull(),
            'promocode_json' => $this->text(),
            'author_json' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addCommentOnTable($this->tableName, 'Статистика');
        $this->addCommentOnColumn($this->tableName, 'date', 'Дата');
        $this->addCommentOnColumn($this->tableName, 'average_check', 'Средний чек');
        $this->addCommentOnColumn($this->tableName, 'promocode_json', 'Промокоды');
        $this->addCommentOnColumn($this->tableName, 'author_json', 'Спикеры');
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
