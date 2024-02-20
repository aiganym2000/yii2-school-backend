<?php

use yii\db\Migration;

/**
 * Class m220814_102400_add_photo_to_course_table
 */
class m220814_102400_add_photo_to_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'photo', $this->string());
        $this->addCommentOnColumn($this->tableName, 'photo', 'Фото');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'photo');
    }
}
