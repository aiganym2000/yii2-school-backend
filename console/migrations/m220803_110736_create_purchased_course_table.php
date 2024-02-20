<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchased_course}}`.
 */
class m220803_110736_create_purchased_course_table extends Migration
{
    public $tableName = '{{%purchased_course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'price' => $this->money(10, 2)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-user_id-purchased_course', $this->tableName, 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-course_id-purchased_course', $this->tableName, 'course_id', '{{%course}}', 'id');

        $this->addCommentOnTable($this->tableName, 'Купленные курсы');
        $this->addCommentOnColumn($this->tableName, 'user_id', 'Пользователь');
        $this->addCommentOnColumn($this->tableName, 'course_id', 'Курс');
        $this->addCommentOnColumn($this->tableName, 'price', 'Цена');
        $this->addCommentOnColumn($this->tableName, 'created_at', 'Время создания');
        $this->addCommentOnColumn($this->tableName, 'updated_at', 'Время обновления');

        $this->addColumn('{{%user}}', 'full_access', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'full_access');

        $this->dropTable($this->tableName);
    }
}
