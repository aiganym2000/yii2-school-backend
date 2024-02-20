<?php

use yii\db\Migration;

/**
 * Class m221017_041124_add_description_to_webinar_table
 */
class m221017_041124_add_description_to_webinar_table extends Migration
{
    public $tableName = '{{%webinar}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'description');
    }
}
