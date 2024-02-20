<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchased_webinar}}`.
 */
class m220922_155159_create_purchased_webinar_table extends Migration
{
    public $tableName = '{{%purchased_webinar}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'webinar_id' => $this->integer()->notNull(),
            'price' => $this->money(10, 2)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user_id-purchased_webinar', $this->tableName, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-webinar_id-purchased_webinar', $this->tableName, 'webinar_id', '{{%webinar}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Купленные вебинары');
        $this->addCommentOnColumn($this->tableName, 'user_id', 'Пользователь');
        $this->addCommentOnColumn($this->tableName, 'webinar_id', 'Вебинар');
        $this->addCommentOnColumn($this->tableName, 'price', 'Цена');
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
