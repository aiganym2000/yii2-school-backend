<?php

use common\models\entity\Course;
use yii\db\Migration;

/**
 * Class m221110_150905_add_position_to_course_table
 */
class m221110_150905_add_position_to_course_table extends Migration
{
    public $tableName = '{{%course}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'position', $this->integer());
        $this->addCommentOnColumn($this->tableName, 'position', 'Позиция');

        $courses = Course::find()->all();
        $i = 1;
        foreach ($courses as $course) {
            $course->position = $i;
            $course->save();
            $i++;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'position');
    }
}
