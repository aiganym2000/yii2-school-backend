<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refferral_user}}`.
 */
class m220802_120326_create_refferral_user_table extends Migration
{
    public $tableName = '{{%refferral_user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'ref_user_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-ref_user_id-refferral_user', $this->tableName, 'ref_user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk-user_id-refferral_user', $this->tableName, 'user_id', '{{%user}}', 'id');

        $this->addColumn('{{%user}}', 'ref_count', $this->integer()->defaultValue(0));
        $this->addColumn('{{%user}}', 'ref_string', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'ref_count');
        $this->dropColumn('{{%user}}', 'ref_string');

        $this->dropTable($this->tableName);
    }
}
