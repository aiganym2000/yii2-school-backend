<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%random_seed}}`.
 */
class m220808_131057_create_random_seed_table extends Migration
{
    public $tableName = '{{%random_seed}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'random_seed' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
