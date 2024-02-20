<?php

use yii\db\Migration;

/**
 * Class m221216_102057_update_created_user_in_tables
 */
class m221216_102057_update_created_user_in_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%author}}', 'created_user', $this->integer()->null());
        $this->alterColumn('{{%banner}}', 'created_user_id', $this->integer()->null());
        $this->alterColumn('{{%category}}', 'created_user', $this->integer()->null());
        $this->alterColumn('{{%course}}', 'created_user', $this->integer()->null());
        $this->alterColumn('{{%lesson}}', 'created_user_id', $this->integer()->null());
        $this->alterColumn('{{%question}}', 'created_user_id', $this->integer()->null());
        $this->alterColumn('{{%webinar}}', 'created_user_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%author}}', 'created_user', $this->integer()->notNull());
        $this->alterColumn('{{%banner}}', 'created_user_id', $this->integer()->notNull());
        $this->alterColumn('{{%category}}', 'created_user', $this->integer()->notNull());
        $this->alterColumn('{{%course}}', 'created_user', $this->integer()->notNull());
        $this->alterColumn('{{%lesson}}', 'created_user_id', $this->integer()->notNull());
        $this->alterColumn('{{%question}}', 'created_user_id', $this->integer()->notNull());
        $this->alterColumn('{{%webinar}}', 'created_user_id', $this->integer()->notNull());
    }
}
